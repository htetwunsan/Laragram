import React, { Component } from 'react';
import WithRouter from "../WithRouter";
import TopNavigation from "../TopNavigation";
import ChatNavheading from "./ChatNavHeading";
import BackButton from "../BackButton";
import SendMessageButton from "./SendMessageButton";
import SendImageButton from './SendImageButton';
import SendLikeButton from './SendLikeButton';
import Message from './Message';
import ProfileImage from '../ProfileImage';
import { AppContext } from './AppContext';
import TimeAgo from 'javascript-time-ago';
import en from 'javascript-time-ago/locale/en.json';
import { Link } from "react-router-dom";
import MessageLikedParticipantsDialog from "./MessageLikedParticipantsDialog";

TimeAgo.addDefaultLocale(en);

class Chat extends Component {

    constructor(props) {
        super(props);

        this.roomId = this.props.params.roomId;

        this.Repo = {
            isFetching: false,
            nextPageUrl: '/api/rooms/' + this.roomId + '/messages',
            getMessages: (initial = false) => {
                if (!this.Repo.nextPageUrl) return;
                if (this.Repo.isFetching) return;
                this.Repo.isFetching = true;

                axios(this.Repo.nextPageUrl)
                    .then(response => {
                        this.Repo.nextPageUrl = response.data.next_page_url;
                        if (initial && response.data.data.length > 0) {
                            axios.get(`/api/messages/${response.data.data[0].id}/seen-by-participants`).then(res => {
                                response.data.data[0].seen_by_participants = res.data.seen_by_participants;
                                this.setStateMessages(response.data.data);
                            });
                            return;
                        }
                        this.setStateMessages(response.data.data);
                    }).finally(() => this.Repo.isFetching = false);
            },
            postTextMessage: content => {
                this.setStateContent("");
                axios.post('/api/rooms/' + this.roomId + '/messages', { content: content, content_type: 'text' })
                    .then(response => {
                        this.setStateMessage(response.data);
                    });
            },
            postLikeMessage: () => {
                axios.post('/api/rooms/' + this.roomId + '/messages', { content_type: 'like' })
                    .then(response => {
                        this.setStateMessage(response.data);
                    });
            },
            postImageMessage: image => {
                const data = new FormData();
                data.append('content', image);
                data.append('content_type', 'image');
                axios.post('/api/rooms/' + this.roomId + '/messages', data, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
                    .then(response => {
                        this.setStateMessage(response.data);
                    });
            },
            getRoom: () => {
                axios('/api/rooms/' + this.roomId).then(response => {
                    this.setState({ room: response.data });
                    this.Repo.getMessages(true);
                });
            },
            markMessageAsSeen: (message) => {
                axios.post(`/api/messages/${message.id}/see`);
            },
            markMessagesAsSeen: () => {
                axios.post(`/api/rooms/${this.roomId}/messages/see`);
            },
            likeMssage: message => {
                axios.post(`/api/rooms/${this.roomId}/messages/${message.id}/like`)
                    .then(response => {
                        this.setStateMessageLikedByParticipants(response.data);
                    });
            },
            unlikeMessage: message => {
                axios.post(`/api/rooms/${this.roomId}/messages/${message.id}/unlike`)
                    .then(response => {
                        this.setStateMessageLikedByParticipants(response.data);
                    });
            },
        };

        this.messagesRef = React.createRef();
        this.textAreaRef = React.createRef();

        this.state = {
            content: "",
            room: null,
            messages: [],
            typingUsers: [],
            showMessageLikedParticipantsDialog: null,
        };
    }

    componentDidMount() {
        this.Repo.getRoom();
        this.Repo.markMessagesAsSeen();
        this.channel = Echo.join(`rooms.${this.roomId}`)
            .here(users => { })
            .joining(user => { })
            .leaving(user => { })
            .error(error => { })
            .listen('RoomUpdated', e => {
                this.setState({ room: e.room });
            })
            .listen('MessageSent', e => {
                this.setStateMessage(e.message);
                this.Repo.markMessageAsSeen(e.message);
            })
            .listen('MessageSeen', e => {
                this.setStateMessageSeenByParticipants(e.message);
            })
            .listen('MessageLiked', e => {
                this.setStateMessageLikedByParticipants(e.message);
            })
            .listen('MessageUnliked', e => {
                this.setStateMessageLikedByParticipants(e.message);
            })
            .listenForWhisper('Typing', e => {
                if (e.isTyping) {
                    if (!this.state.typingUsers.some(user => user.id == e.user.id)) {
                        this.setState(({ typingUsers }) => ({
                            typingUsers: [...typingUsers, e.user]
                        }));
                    }
                } else {
                    this.setState(({ typingUsers }) => ({
                        typingUsers: typingUsers.filter(user => user.id != e.user.id)
                    }));
                }
            });
    }

    componentWillUnmount() {
        Echo.leave(`rooms.${this.roomId}`);
    }

    handleChangeContent = e => {
        this.setStateContent(e.target.value);
    }

    insertDateMessages = messages => {
        return messages.flatMap((m, i) => {
            if (i == 0) return m;
            const pm = messages[i - 1];
            if (pm.id < 0) {
                return m;
            }
            if (Math.abs(new Date(m.created_at) - new Date(pm.created_at)) >= 900000) {
                return [{ ...pm, id: -pm.id, content_type: 'date', content: timeSince(pm.created_at) }, m];
            }
            return m;
        });
    }

    setStateMessages = newMessages => {
        this.setState(({ messages }) => ({ messages: this.insertDateMessages([...messages, ...newMessages]) }));
    }

    setStateMessage = message => {
        this.setState(({ messages }) => ({
            messages: this.insertDateMessages([message, ...messages])
        }));
    }

    setStateContent = content => {
        this.setState({ content: content }, () => {
            const { current } = this.textAreaRef;
            current.style.height = 'auto';
            current.style.height = current.scrollHeight + 'px';
        });

        this.channel.whisper('Typing', {
            isTyping: content.length > 0,
            user: this.context.authUser,
        });
    }

    setStateMessageSeenByParticipants = newMessage => {
        this.setState(({ messages }) => ({
            messages: messages.map(message => {
                if (message.id == newMessage.id) {
                    message.seen_by_participants = newMessage.seen_by_participants;
                }
                return message;
            })
        }));
    }

    setStateMessageLikedByParticipants = newMessage => {
        this.setState(({ messages }) => ({
            messages: messages.map(message => {
                if (message.id == newMessage.id) {
                    message.liked_by_participants = newMessage.liked_by_participants;
                }
                return message;
            })
        }));
    }

    handleScroll = _.throttle(e => {
        const scrollOffset = 300;
        if (e.target.scrollTop - scrollOffset <= 0) {
            this.Repo.getMessages();
        }
    }, 500)

    handleClickSendMessage = e => {
        const { content } = this.state;
        if (!content) return;
        this.Repo.postTextMessage(content);
    }

    isMe = (message) => {
        return this.context.authUser.id == message.user_id
    }

    handleClickLikeMessage = (e, message) => {
        this.Repo.likeMssage(message);
    }

    handleClickUnlikeMessage = (e, message) => {
        this.Repo.unlikeMessage(message);
    }

    showMessageLikedParticipantsDialog = (message) => {
        this.setState({ showMessageLikedParticipantsDialog: message });
    }

    hideMessageLikedParticipantsDialog = () => {
        this.setState({ showMessageLikedParticipantsDialog: null });
    }

    handleClickDeleteChat = e => {
        axios.post('/api/rooms/' + this.roomId + '/delete-room').then(response => {
            this.props.navigate('/direct/inbox');
        });
    }

    renderRight = () => {
        return (
            <div className="flex flex-col items-stretch">
                <Link className="flex items-center justify-center" to="details">
                    <svg aria-label="View Thread Details" color="#262626" fill="#262626" height="24" role="img" viewBox="0 0 24 24" width="24">
                        <circle cx="12.001" cy="12.005" fill="none" r="10.5" stroke="currentColor" strokeLinecap="round" strokeLinejoin="round" strokeWidth="2"></circle>
                        <circle cx="11.819" cy="7.709" r="1.25"></circle>
                        <line fill="none" stroke="currentColor" strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" x1="10.569" x2="13.432" y1="16.777" y2="16.777"></line>
                        <polyline fill="none" points="10.569 11.05 12 11.05 12 16.777" stroke="currentColor" strokeLinecap="round" strokeLinejoin="round" strokeWidth="2">

                        </polyline>
                    </svg>
                </Link>
            </div>
        );
    }

    render() {
        const { room, content, messages, typingUsers, showMessageLikedParticipantsDialog } = this.state;
        const { authUser } = this.context;
        return <>
            <section id="section_main" className="flex-grow flex flex-col items-stretch overflow-hidden relative">
                <TopNavigation
                    left={<BackButton handleClick={e => this.props.navigate(-1)} />}
                    center={<ChatNavheading room={room} />}
                    right={this.renderRight()} />

                <main className="bg-white flex-grow flex flex-col-reverse items-stretch overflow-y-auto overflow-x-hidden px-4 pt-4" role="main"
                    ref={this.messagesRef} onScroll={this.handleScroll}>
                    {
                        typingUsers.map(user => {
                            return (
                                <div className="self-start flex flex-col items-stretch" style={{ maxWidth: '70%' }} key={user.id}>
                                    <div className="flex flex-row items-center">
                                        <div className="flex items-center justify-center">
                                            <div className="flex items-center justify-center w-6 h-6 rounded-full relative mr-3">
                                                <ProfileImage url={user.profile_image} />
                                            </div>
                                        </div>

                                        <div className="text-sm text-triple142 leading-18px">
                                            {
                                                room.type == 'group' &&
                                                <span>{user.name} </span>
                                            }
                                            Typing...
                                        </div>
                                    </div>
                                </div>
                            );
                        })
                    }
                    {
                        room?.type == 'direct' &&
                        messages.length > 0 &&
                        this.isMe(messages[0]) &&
                        messages[0].seen_by_participants?.length > 0 &&
                        <div className="self-end flex flex-col items-stretch" style={{ maxWidth: '70%' }}>
                            <span className="block text-xs text-triple142 leading-4" style={{ marginTop: '-2px' }}>
                                Seen {
                                    new TimeAgo().format(Date.parse(messages[0].seen_by_participants[0].pivot.created_at), 'round-minute')
                                }
                            </span>
                        </div>
                    }
                    {
                        room?.type == 'group' &&
                        messages.length > 0 &&
                        this.isMe(messages[0]) &&
                        messages[0].seen_by_participants?.length > 0 &&
                        <div className="self-end flex flex-col items-stretch" style={{ maxWidth: '70%' }}>
                            <span className="block text-xs text-triple142 leading-4" style={{ marginTop: '-2px' }}>
                                Seen By {
                                    messages[0].seen_by_participants.map(participant => participant.user.name).join(', ')
                                }
                            </span>
                        </div>
                    }
                    {
                        messages.map(
                            (message, index) => <Message message={message} room={room} index={index}
                                handleClickLikeMessage={this.handleClickLikeMessage}
                                showMessageLikedParticipantsDialog={this.showMessageLikedParticipantsDialog}
                                key={message.id} />
                        )
                    }
                </main>

                <div className="flex-none flex flex-col items-stretch order-last z-50">
                    {
                        room?.type == 'direct' &&
                        (() => {
                            const otherParticipant = room.participants.filter(p => p.id != authUser.id)[0];
                            if (otherParticipant.user.is_blocked_by_auth_user) {
                                return (
                                    <div className="bg-triple250 flex flex-col items-stretch" style={{ height: '50px' }}>
                                        <div className="flex-grow flex items-center justify-center gap-x-2">
                                            <div className="text-sm text-triple38 text-center leading-18px -mb-1" style={{ marginTop: '-3px' }}>
                                                You blocked {otherParticipant.user.username}.
                                            </div>
                                            <button className="flex flex-col items-stretch" type="button"
                                                onClick={this.handleClickDeleteChat}>
                                                <span className="block text-sm text-fb_blue text-center leading-18px -mb-1" style={{ marginTop: '-3px' }}>
                                                    Delete chat.
                                                </span>
                                            </button>
                                        </div>
                                    </div>
                                );
                            }
                            return (
                                <div className="flex flex-col items-stretch p-4">
                                    <div className="flex items-center border border-triple219" style={{ borderRadius: '22px', padding: '0 8px 0 11px' }}>
                                        <div className="flex flex-grow items-center mr-1">
                                            <textarea
                                                className="flex-grow text-sm text-triple38 leading-18px break-words resize-none border-none focus:outline-none focus:ring-0"
                                                style={{ maxHeight: '90px', padding: '8px 9px 8px 9px' }}
                                                ref={this.textAreaRef}
                                                value={content}
                                                onChange={this.handleChangeContent}
                                                placeholder="Message..."
                                                autoComplete="off"
                                                required
                                                rows="1"></textarea>
                                        </div>

                                        {content && <SendMessageButton handleClick={this.handleClickSendMessage} />}

                                        {!content && <SendImageButton postImageMessage={this.Repo.postImageMessage} />}
                                        {!content && <SendLikeButton handleClick={e => this.Repo.postLikeMessage()} />}
                                    </div>
                                </div>
                            );
                        })()
                    }
                    {
                        room?.type != 'direct' &&
                        <div className="flex flex-col items-stretch p-4">
                            <div className="flex items-center border border-triple219" style={{ borderRadius: '22px', padding: '0 8px 0 11px' }}>
                                <div className="flex flex-grow items-center mr-1">
                                    <textarea
                                        className="flex-grow text-sm text-triple38 leading-18px break-words resize-none border-none focus:outline-none focus:ring-0"
                                        style={{ maxHeight: '90px', padding: '8px 9px 8px 9px' }}
                                        ref={this.textAreaRef}
                                        value={content}
                                        onChange={this.handleChangeContent}
                                        placeholder="Message..."
                                        autoComplete="off"
                                        required
                                        rows="1"></textarea>
                                </div>

                                {content && <SendMessageButton handleClick={this.handleClickSendMessage} />}

                                {!content && <SendImageButton postImageMessage={this.Repo.postImageMessage} />}
                                {!content && <SendLikeButton handleClick={e => this.Repo.postLikeMessage()} />}
                            </div>
                        </div>
                    }
                </div>
            </section>
            {
                showMessageLikedParticipantsDialog &&
                <MessageLikedParticipantsDialog
                    message={showMessageLikedParticipantsDialog}
                    hideMessageLikedParticipantsDialog={this.hideMessageLikedParticipantsDialog}
                    handleClickUnlikeMessage={this.handleClickUnlikeMessage} />
            }
        </>;
    }
}

Chat.contextType = AppContext;

export default WithRouter(Chat);
