import React, { useState, useContext } from 'react';
import ProfileImage from "../ProfileImage";
import { AppContext } from './AppContext';

export default function MessageLikedParticipantsDialog({ message, hideMessageLikedParticipantsDialog, handleClickUnlikeMessage }) {

    const context = useContext(AppContext);

    const [animation, setAnimation] = useState({
        animation: 'open-message-liked-participants 0.3s ease 0s'
    });

    const isMe = participant => {
        return context.authUser.id == participant.user_id;
    };

    const closeDialog = () => {
        setAnimation({
            animation: 'close-message-liked-participants 0.3s ease 0s'
        });
        setTimeout(() => {
            hideMessageLikedParticipantsDialog();
        }, 300);
    }

    const handleClickOutside = e => {
        closeDialog();
    };

    const handleClickUnlike = (e, participant) => {
        if (!isMe(participant)) return;
        handleClickUnlikeMessage(e, message);
        closeDialog();
    }

    return (
        <div className="bg-black bg-opacity-65 absolute inset-0 flex flex-col items-stretch justify-end overflow-hidden z-1000"
            role="button" onClick={handleClickOutside}>
            <div className="bg-white flex flex-col items-stretch overflow-hidden rounded-t-xl"
                style={animation}
                onClick={e => e.stopPropagation()}>
                <div className="flex flex-col items-stretch overflow-hidden">
                    <div className="flex flex-col items-stretch p-3">
                        <div className="bg-triple219 h-1 w-12 rounded-sm mx-auto "></div>
                    </div>
                </div>
                <div className="flex flex-col items-stretch overflow-hidden">
                    <div className="flex flex-col items-stretch justify-center border-b border-triple219 overflow-hidden"
                        style={{ height: '43px' }}>
                        <h1 className="text-base text-triple38 text-center font-semibold leading-6">Reactions</h1>
                    </div>
                    <div className="flex flex-col items-stretch overflow-y-auto" style={{ maxHeight: '216px' }}>
                        {
                            message.liked_by_participants.map(p => {
                                const { user } = p;
                                return (
                                    <button className="flex flex-col items-stretch py-2" key={p.id} type="button"
                                        onClick={e => handleClickUnlike(e, p)}>
                                        <div className="flex flex-row px-4 py-2">
                                            <div className="flex flex-col items-stretch w-10 h-10 mr-3">
                                                <ProfileImage url={user.profile_image} />
                                            </div>

                                            <div className="flex-grow flex flex-col items-stretch justify-center">
                                                <div className="flex flex-col items-stretch">
                                                    <span className="block text-sm text-triple38 text-left leading-18px overflow-hidden overflow-ellipsis"
                                                        style={{ marginTop: '-3px', marginBottom: '-4px' }}>
                                                        {user.username}
                                                    </span>
                                                </div>

                                                {
                                                    isMe(p) &&
                                                    <div className="flex flex-col items-stretch mt-2">
                                                        <span className="block text-sm text-triple142 text-left leading-18px overflow-hidden overflow-ellipsis"
                                                            style={{ marginTop: '-3px', marginBottom: '-4px' }}>
                                                            Tap to remove
                                                        </span>
                                                    </div>
                                                }
                                            </div>

                                            <div className="flex flex-col items-stretch justify-center ml-2">
                                                <span className="block text-sm text-triple38 leading-18px"
                                                    style={{ marginTop: '-3px', marginBottom: '-4px' }}>
                                                    ❤️
                                                </span>
                                            </div>
                                        </div>
                                    </button>
                                );
                            })
                        }
                    </div>
                </div>
            </div>
        </div>
    );
}
