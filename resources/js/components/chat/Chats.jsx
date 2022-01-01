import React, { Component } from 'react';
import { Link, MemoryRouter } from "react-router-dom";
import ProfileImage from "../ProfileImage";
import TopNavigation from '../TopNavigation';
import BackButton from "../BackButton";
import NewMessageButton from "./NewMessageButton";
import NavHeading from "../NavHeading";
import { AppContext } from "./AppContext";
import clsx from "clsx";

export default class Chats extends Component {

    constructor(props, context) {
        super(props, context);

        this.RoomRepo = {
            isFetching: false,
            nextPageUrls: [`/api/rooms`],
            fetchNext: () => {
                const nextPageUrl = this.RoomRepo.nextPageUrls[this.RoomRepo.nextPageUrls.length - 1];
                if (!nextPageUrl) return;
                if (this.RoomRepo.isFetching) return;
                this.RoomRepo.isFetching = true;

                axios.get(nextPageUrl).then(response => {
                    this.RoomRepo.nextPageUrls.push(response.data.next_page_url);
                    this.setState(prevState => ({ rooms: [...prevState.rooms, ...response.data.data] }));
                }).finally(() => this.RoomRepo.isFetching = false);
            },
            refreshAll: async () => {
                this.RoomRepo.isFetching = true;
                const rooms = [];
                for (const nextPageUrl of this.RoomRepo.nextPageUrls.slice(0, -1)) {
                    try {
                        const response = await axios.get(nextPageUrl);
                        rooms.push(...response.data.data);
                    } catch (error) {
                        this.RoomRepo.isFetching = false;
                        return;
                    }
                }
                this.setState({ rooms: rooms });
                this.isFetching = false;
            }
        };

        this.state = {
            rooms: []
        };
    }

    componentDidMount() {
        this.RoomRepo.fetchNext();
        Echo.private(`users.${this.context.authUser.id}.rooms`)
            .listen('RoomUpdated', e => {
                this.RoomRepo.refreshAll();
            });
    }

    componentWillUnmount() {
        Echo.leave(`users.${this.context.authUser.id}.rooms`);
    }

    handleScroll = _.throttle(e => {
        const scrollOffset = 1000;
        if (e.target.scrollTop + scrollOffset >= e.target.scrollHeight) {
            this.RoomRepo.fetchNext();
        }
    }, 500)

    render() {
        const { rooms } = this.state;
        return (
            <section id="section_main" className="flex-grow flex flex-col items-stretch overflow-y-auto no-scrollbar" onScroll={this.handleScroll}>
                <TopNavigation left={<BackButton handleClick={e => window.location.href = window.location.origin} />} center={<NavHeading name="Direct" />} right={<NewMessageButton />} />

                <main className="bg-white flex-grow flex flex-col items-stretch" role="main">
                    <div className="flex flex-col items-stretch pt-2">
                        {rooms.map(room => {
                            const { participants } = room;
                            const participant = participants.find(p => p.user_id == this.context.authUser.id);
                            const otherParticipants = room.type == 'solo' ? participants : participants.filter(p => p.user_id != this.context.authUser.id);
                            return (
                                <Link className="flex flex-col items-stretch" to={"/direct/r/" + room.id} key={room.id}>
                                    <div className="flex px-4 py-2">
                                        <div className="flex flex-none items-center justify-center rounded-full mr-3 relative" style={{ width: "56px", height: "56px" }}>
                                            <ProfileImage url={otherParticipants[0].user.profile_image} />

                                            <div className="absolute w-5 h-5 border-white rounded-full mt-0.5 ml-0.5 z-10"
                                                style={{ left: '33px', top: '33px', backgroundColor: "rgb(120, 222, 69)", borderWidth: '3.5px' }}></div>
                                        </div>

                                        <div className="flex-grow flex flex-col items-stretch justify-center overflow-hidden">
                                            <div className="flex">
                                                <span className="block text-sm text-triple38 leading-18px overflow-hidden overflow-ellipsis whitespace-nowrap" style={{ marginTop: '-3px', marginBottom: '-4px' }}>
                                                    <span className={clsx(!room.latest_message?.seen_by_auth_user && "font-semibold")}>
                                                        {otherParticipants[0].user.name}
                                                    </span>
                                                </span>
                                            </div>

                                            {
                                                room.latest_message && <div className="flex flex-col items-stretch mt-2">
                                                    <div className="flex flex-col items-stretch" style={{ marginTop: '-3px', marginBottom: '-4px' }}>
                                                        <div className="flex">
                                                            {room.latest_message.content_type == 'text' &&
                                                                <span className="block text-sm text-triple142 leading-18px overflow-hidden overflow-ellipsis whitespace-nowrap">
                                                                    <span className={clsx(!room.latest_message?.seen_by_auth_user && "text-triple38 font-semibold")}>
                                                                        {room.latest_message.content}
                                                                    </span>
                                                                </span>
                                                            }
                                                            {room.latest_message.content_type == 'like' &&
                                                                <span className="block text-sm leading-18px">❤️</span>
                                                            }
                                                            {room.latest_message.content_type == 'image' &&
                                                                <span className="block text-sm text-triple142 leading-18px overflow-hidden overflow-ellipsis whitespace-nowrap">
                                                                    <span className={clsx(!room.latest_message?.seen_by_auth_user && "text-triple38 font-semibold")}>
                                                                        Sent an image
                                                                    </span>
                                                                </span>
                                                            }
                                                            <span className="block text-sm text-triple38 leading-18px whitespace-nowrap mx-1">·</span>
                                                            <time className="block text-sm text-triple142 leading-18px whitespace-nowrap"
                                                                dateTime={room.updated_at}>
                                                                {room.formatted_updated_at}
                                                            </time>
                                                            {
                                                                participant.room_muted_at && <span className="block mute-icon ml-1"></span>
                                                            }
                                                        </div>
                                                    </div>
                                                </div>
                                            }
                                            {
                                                !room.latest_message && <div className="flex flex-col items-stretch mt-2">
                                                    <div className="flex flex-col items-stretch" style={{ marginTop: '-3px', marginBottom: '-4px' }}>
                                                        <div className="flex">
                                                            <span className="block text-sm text-triple142 leading-18px overflow-hidden overflow-ellipsis whitespace-nowrap">
                                                                <span>
                                                                    Active now
                                                                </span>


                                                            </span>

                                                            {
                                                                participant.room_muted_at && <span className="block mute-icon ml-1"></span>
                                                            }

                                                        </div>
                                                    </div>
                                                </div>
                                            }
                                        </div>

                                        {(room.latest_message && !room.latest_message.seen_by_auth_user) &&
                                            <div className="flex-none flex items-center justify-center ml-2">
                                                <div className="w-2 h-2 bg-fb_blue rounded-full"></div>
                                            </div>}
                                    </div>
                                </Link>
                            );
                        })}
                    </div>
                </main>
            </section>
        );
    }
}

Chats.contextType = AppContext;
