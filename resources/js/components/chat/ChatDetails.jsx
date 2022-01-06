import React, { useState, useEffect, useContext } from 'react';
import { Link, useNavigate, useParams } from "react-router-dom";
import BackButton from '../BackButton';
import NavHeading from "../NavHeading";
import ProfileImage from '../ProfileImage';
import TopNavigation from '../TopNavigation';
import { AppContext } from "./AppContext";
import BlockOrUnblockButton from './BlockOrUnblockButton';
import BlockDialog from './BlockDialog';
import UnblockDialog from "./UnblockDialog";
import ChatDetailsAdminDialog from './ChatDetailsAdminDialog';

export default function ChatDetails() {

    const { roomId } = useParams();
    const navigate = useNavigate();
    const { authUser } = useContext(AppContext);
    const [room, setRoom] = useState(null);
    const [originalRoomName, setOriginalRoomName] = useState(null);
    const [showBlockDialog, setShowBlockDialog] = useState(null);
    const [showUnblockDialog, setShowUnblockDialog] = useState(null);
    const [showChatDetailsAdminDialog, setShowChatDetailsAdminDialog] = useState(null);

    useEffect(() => {
        axios.get('/api/rooms/' + roomId).then(response => {
            setRoom(response.data);
            setOriginalRoomName(response.data.name);
        });
    }, []);

    const handleChangeMute = e => {
        const action = e.target.checked ? '/mute' : '/unmute';
        axios.post('/api/rooms/' + roomId + action).then(response => {
            setRoom(response.data);
        });
    };

    const handleClickDeleteChat = e => {
        axios.post('/api/rooms/' + roomId + '/delete-room').then(response => {
            navigate('/direct/inbox', { replace: true });
        });
    };

    const handleClickLeaveChat = e => {
        const p = room.participants.find(p => p.user_id == authUser.id);
        axios.delete('/api/rooms/' + roomId + '/participants/' + p.id).then(response => {
            navigate('/direct/inbox', { replace: true });
        });
    };

    const handleShowBlockDialog = participant => {
        if (participant) {
            setShowBlockDialog(participant);
        } else {
            setShowBlockDialog(room.participants.filter(p => p.user_id != authUser.id)[0]);
        }
    };

    const handleShowUnblockDialog = participant => {
        if (participant) {
            setShowUnblockDialog(participant);
        } else {
            setShowUnblockDialog(room.participants.filter(p => p.user_id != authUser.id)[0]);
        }
    };

    const handleShowChatDetailsAdminDialog = participant => {
        setShowChatDetailsAdminDialog(participant);
    };

    const handleClickBlock = (e, participant) => {
        axios.post('/api/users/' + participant.user_id + '/block').then(response => {
            showBottomToast('Blocked');
            handleHideBlockDialog();
            room?.participants.map(p => {
                if (p.id == participant.id) {
                    p.user.is_blocked_by_auth_user = true;
                }
                return p;
            });
            setRoom({ ...room });
        });
    };

    const handleClickUnblock = (e, participant) => {
        axios.post('/api/users/' + participant.user_id + '/unblock').then(response => {
            showBottomToast('Unblocked');
            handleHideUnblockDialog();
            room?.participants.map(p => {
                if (p.id == participant.id) {
                    p.user.is_blocked_by_auth_user = false;
                }
                return p;
            });
            setRoom({ ...room });
        })
    };

    const handleClickRemoveFromGroup = (e, participant) => {
        return axios.delete('/api/rooms/' + roomId + '/participants/' + participant.id).then(response => {
            return axios.get('/api/rooms/' + roomId).then(response => {
                setRoom(response.data);
                setOriginalRoomName(response.data.name);
            });
        }).finally(() => {
            handleHideChatDetailsAdminDialog();
        });
    };

    const handleClickMakeAdmin = (e, participant) => {
        return axios.patch('/api/rooms/' + roomId + '/participants/' + participant.id, { is_admin: true }).then(response => {
            return axios.get('/api/rooms/' + roomId).then(response => {
                setRoom(response.data);
                setOriginalRoomName(response.data.name);
            });
        }).finally(() => {
            handleHideChatDetailsAdminDialog();
        });
    };

    const handleClickRemoveAsAdmin = (e, participant) => {
        return axios.patch('/api/rooms/' + roomId + '/participants/' + participant.id, { is_admin: false }).then(response => {
            return axios.get('/api/rooms/' + roomId).then(response => {
                setRoom(response.data);
                setOriginalRoomName(response.data.name);
            });
        }).finally(() => {
            handleHideChatDetailsAdminDialog();
        });
    };

    const handleHideBlockDialog = () => {
        setShowBlockDialog(null);
    };

    const handleHideUnblockDialog = () => {
        setShowUnblockDialog(null);
    };

    const handleHideChatDetailsAdminDialog = () => {
        setShowChatDetailsAdminDialog(null);
    };

    const handleChangeRoomName = e => {
        setRoom({ ...room, name: e.target.value });
    };

    const handleClickDone = e => {
        axios.patch('/api/rooms/' + roomId, { name: room.name }).then(response => {
            setOriginalRoomName(response.data.name);
        });
    };

    const isMuted = () => {
        if (!room) return false;
        if (!room.participants.find(e => e.user_id == authUser.id).room_muted_at) return false;
        return true;
    };

    const renderDone = () => {
        return (
            <div className="flex flex-col items-stretch w-8 h-6">
                {
                    room?.name != originalRoomName &&
                    <button className="flex flex-col items-stretch text-sm text-fb_blue text-center font-semibold leading-18px"
                        type="button"
                        onClick={handleClickDone}>
                        Done
                    </button>
                }
            </div>
        );
    };


    return <>
        <section id="section_main" className="flex-grow flex flex-col items-stretch overflow-hidden">
            <TopNavigation
                left={<BackButton handleClick={e => navigate(-1)} />}
                center={<NavHeading name="Details" />}
                right={renderDone()} />

            {(() => {
                if (!room) return null;
                let { participants } = room;
                if (room.type == 'direct') {
                    participants = participants.filter(p => p.user_id != authUser.id);
                }

                return (
                    <main className="bg-white flex-grow flex flex-col items-stretch overflow-y-auto overflow-x-hidden" role="main">

                        <div className="flex flex-col items-stretch p-4 pt-5 border-b border-triple219">
                            {
                                room.type == 'group' &&
                                <div className="flex items-center mb-5">
                                    <span className="block text-sm text-triple38 leading-18px">Group Name:</span>
                                    <input className="flex-grow block text-sm text-triple38 leading-18px overflow-hidden overflow-ellipsis px-2 py-0 border-0 focus:ring-0" aria-label="Group name text input"
                                        placeholder="Add a name"
                                        spellCheck="true"
                                        type="text"
                                        onChange={handleChangeRoomName}
                                        value={room.name} />
                                </div>
                            }
                            <label className="flex items-center">
                                <input className="w-4 h-4 bg-white text-triple38 border border-triple219 rounded ml-1 mr-2 focus:ring-0"
                                    type="checkbox" onChange={handleChangeMute} checked={isMuted()} />
                                <div className="text-sm text-triple-38 leading-18px" style={{ marginTop: '-3px', marginBottom: '-4px' }}>
                                    Mute Messages
                                </div>
                            </label>
                        </div>

                        <div className="flex flex-col items-stretch border-b border-triple219">
                            <div className="flex flex-col items-stretch my-4">
                                <div className="flex items-center px-4 my-2">
                                    <div className="flex-grow flex flex-col items-stretch">
                                        <h4 className="text-base text-triple38 font-semibold leading-6 -my-1.5">Members</h4>
                                    </div>

                                    <Link className="text-sm text-fb_blue text-center font-semibold leading-18px"
                                        to="add">
                                        Add People
                                    </Link>
                                </div>

                                {
                                    participants.map(p => {
                                        const { user } = p;
                                        return (
                                            <div className="flex flex-col items-stretch" key={user.id}>
                                                <div className="flex items-center px-4 py-2">
                                                    <div className="flex flex-col items-stretch w-14 h-14 mr-3">
                                                        <ProfileImage url={user.profile_image} />
                                                    </div>

                                                    <div className="flex-grow flex flex-col items-stretch justify-center overflow-hidden">
                                                        <span className="block text-sm text-triple38 leading-18px overflow-hidden overflow-ellipsis whitespace-nowrap" style={{ marginTop: '-3px', marginBottom: '-4px' }}>
                                                            <span className="font-semibold">
                                                                {user.username}
                                                            </span>
                                                        </span>

                                                        <div className="flex flex-col items-stretch mt-2">
                                                            <div className="flex flex-col items-stretch">
                                                                <span className="flex text-sm text-triple142 leading-18px overflow-hidden overflow-ellipsis whitespace-nowrap" style={{ marginTop: '-3px', marginBottom: '-4px' }}>
                                                                    {
                                                                        p.is_admin &&
                                                                        "Admin"
                                                                    }
                                                                    {
                                                                        p.is_admin &&
                                                                        <span className="block text-sm text-triple38 leading-18px mx-1">·</span>
                                                                    }
                                                                    {user.name}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    {
                                                        (user.id != authUser.id && participants.find(p => p.user_id == authUser.id)?.is_admin) &&
                                                        <div className="flex flex-col items-stretch ml-2">
                                                            <button className="flex flex-col items-stretch p-2"
                                                                type="button"
                                                                onClick={e => handleShowChatDetailsAdminDialog(p)}>
                                                                <svg aria-label="Edit options" color="#262626" fill="#262626" height="24" role="img" viewBox="0 0 24 24" width="24">
                                                                    <circle cx="12" cy="12" r="1.5"></circle>
                                                                    <circle cx="6" cy="12" r="1.5"></circle>
                                                                    <circle cx="18" cy="12" r="1.5"></circle>
                                                                </svg>
                                                            </button>
                                                        </div>
                                                    }
                                                </div>
                                            </div>
                                        );
                                    })
                                }

                            </div>
                        </div>

                        <div className="flex flex-col items-stretch border-b border-triple219">
                            {
                                room.type == 'group' &&
                                <>
                                    <div className="flex flex-col items-stretch m-4">
                                        <button className="flex flex-col items-stretch" type="button" onClick={handleClickLeaveChat}>
                                            <span className="block text-sm text-error text-left leading-18px" style={{ marginTop: '-3px', marginBottom: '-4px' }}>
                                                Leave Chat
                                            </span>
                                        </button>
                                    </div>
                                    <div className="flex flex-col items-stretch ml-4 mb-4">
                                        <div className="text-xs text-triple142 leading-4 -mt-0.5" style={{ marginBottom: '-3px' }}>
                                            You won't get messages from this group unless someone adds you back to the chat.
                                        </div>
                                    </div>
                                </>
                            }
                            {
                                room.type != 'solo' &&
                                <div className="flex flex-col items-stretch m-4">
                                    <button className="flex flex-col items-stretch" type="button" onClick={handleClickDeleteChat}>
                                        <span className="block text-sm text-error text-left leading-18px" style={{ marginTop: '-3px', marginBottom: '-4px' }}>
                                            Delete Chat
                                        </span>
                                    </button>
                                </div>
                            }

                            {
                                room.type == 'direct' &&
                                <div className="flex flex-col items-stretch m-4">
                                    <BlockOrUnblockButton participant={participants[0]}
                                        handleShowBlockDialog={handleShowBlockDialog}
                                        handleShowUnblockDialog={handleShowUnblockDialog} />
                                </div>
                            }

                            {/* {
                                room.type != 'solo' &&
                                <div className="flex flex-col items-stretch m-4">
                                    <button className="flex flex-col items-stretch" type="button" onClick={handleClickReport}>
                                        <span className="block text-sm text-error text-left leading-18px" style={{ marginTop: '-3px', marginBottom: '-4px' }}>
                                            Report
                                        </span>
                                    </button>
                                </div>
                            } */}
                        </div>
                    </main>
                );
            })()}
        </section>

        {
            showBlockDialog &&
            <BlockDialog participant={showBlockDialog}
                handleClickBlock={handleClickBlock}
                hideDialog={handleHideBlockDialog} />
        }

        {
            showUnblockDialog &&
            <UnblockDialog participant={showUnblockDialog}
                handleClickUnblock={handleClickUnblock}
                hideDialog={handleHideUnblockDialog} />
        }
        {
            showChatDetailsAdminDialog &&
            <ChatDetailsAdminDialog participant={showChatDetailsAdminDialog}
                handleClickRemoveFromGroup={handleClickRemoveFromGroup}
                handleClickMakeAdmin={handleClickMakeAdmin}
                handleClickRemoveAsAdmin={handleClickRemoveAsAdmin}
                hideDialog={handleHideChatDetailsAdminDialog}
            />
        }
    </>;
}
