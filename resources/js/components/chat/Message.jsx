import React, { useContext } from 'react';
import { AppContext } from "./AppContext";
import ProfileImage from '../ProfileImage';
import clsx from 'clsx';

export default function Message({ message, handleClickLikeMessage, showMessageLikedDialog }) {

    const context = useContext(AppContext);

    const isMe = () => {
        return context.authUser.id == message.user_id;
    };

    const handleClick = e => {
        e.stopPropagation();
        showMessageLikedDialog(message);
    };

    const renderLike = () => {
        if (!message.liked_by_participants || message.liked_by_participants.length <= 0) return null;
        return <>
            <div className="order-last h-3"></div>
            <div className="bg-triple239 absolute bottom-1 right-0 z-10 flex flex-col items-stretch p-1.5 border-2 border-white"
                style={{ borderRadius: '20px' }}>
                <div className="flex flex-row gap-x-1" role="button" onClick={handleClick}>
                    <div className="flex flex-col items-stretch">
                        <div className="text-xs text-center font-semibold leading-18px h-3.5 w-3.5 ">
                            ❤️
                        </div>
                    </div>
                    {
                        message.liked_by_participants.length > 1 &&
                        message.liked_by_participants.map(p => {
                            return (
                                <div className="flex items-center justify-center h-3.5 w-3.5 rounded-full" key={p.id}>
                                    <ProfileImage url={p.user.profile_image} />
                                </div>
                            );
                        })
                    }
                </div>
            </div>
        </>;
    };

    return <>

        <div className={clsx({ 'self-end': isMe() }, { 'self-start': !isMe() }, 'flex', 'flex-col', 'items-stretch')} style={{ maxWidth: '60%' }}>

            <div className={clsx('flex', 'flex-col', 'items-stretch', 'pb-2', 'relative')} role="button"
                onDoubleClick={e => handleClickLikeMessage(e, message)}>
                {renderLike()}
                <div className="flex flex-row items-center">
                    {
                        !isMe() &&
                        <div className="self-end flex items-center justify-center">
                            <div className="flex items-center justify-center w-6 h-6 rounded-full relative mr-2 mb-2">
                                <ProfileImage url={message.participant.user.profile_image} />
                            </div>
                        </div>
                    }

                    {
                        message.content_type == 'text' &&
                        <div className={clsx({ 'bg-triple239': isMe() }, 'flex', 'flex-col', 'items-stretch', 'border', 'border-triple239')} style={{ borderRadius: '22px' }}>
                            <div className="flex flex-col items-stretch p-4" style={{ minHeight: '44px' }}>
                                <div className="text-sm text-triple38 leading-18px break-words whitespace-normal" style={{ marginTop: '-3px', marginBottom: '-4px', wordBreak: 'break-word' }}>
                                    {message.content}
                                </div>
                            </div>
                        </div>
                    }
                    {
                        message.content_type == 'like' &&
                        <svg aria-label="Like" color="#ed4956" fill="#ed4956" height="44" role="img" viewBox="0 0 48 48" width="44">
                            <path d="M34.6 3.1c-4.5 0-7.9 1.8-10.6 5.6-2.7-3.7-6.1-5.5-10.6-5.5C6 3.1 0 9.6 0 17.6c0 7.3 5.4 12 10.6 16.5.6.5 1.3 1.1 1.9 1.7l2.3 2c4.4 3.9 6.6 5.9 7.6 6.5.5.3 1.1.5 1.6.5s1.1-.2 1.6-.5c1-.6 2.8-2.2 7.8-6.8l2-1.8c.7-.6 1.3-1.2 2-1.7C42.7 29.6 48 25 48 17.6c0-8-6-14.5-13.4-14.5z">

                            </path>
                        </svg>
                    }
                    {
                        message.content_type == 'image' &&
                        <div className="flex items-center justify-center">
                            <img className="w-full h-full object-cover" style={{ borderRadius: '22px' }} src={message.content} />
                        </div>
                    }
                </div>
            </div>
        </div>
    </>;
}
