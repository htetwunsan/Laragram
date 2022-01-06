import React, { useContext } from 'react';
import ProfileImage from '../ProfileImage';
import { AppContext } from './AppContext';

export default function ChatNavHeading({ room }) {
    if (!room) return null;

    const { authUser } = useContext(AppContext);

    return (
        <h1 className="flex-auto flex flex-col items-stretch overflow-hidden">
            <div className="flex items-center">
                <div className="flex flex-col items-stretch w-8 h-8 rounded-full relative">
                    {
                        room.type == 'solo' &&
                        <ProfileImage url={room.participants[0].user.profile_image} />
                    }
                    {
                        room.type == 'direct' &&
                        <ProfileImage url={room.participants.filter(p => p.user_id != authUser.id)[0].user.profile_image} />
                    }
                    {
                        room.type == 'group' &&
                        room.participants.filter(p => p.user_id != authUser.id).slice(0, 2).map((p, i) => {
                            if (i == 0) {
                                return (<div className="flex w-5 h-5" key={p.id}>
                                    <ProfileImage url={p.user.profile_image} />
                                </div>);
                            }
                            return (
                                <div className="absolute right-0 bottom-0 flex w-5 h-5" key={p.id}>
                                    <ProfileImage url={p.user.profile_image} />
                                </div>
                            );
                        })
                    }


                    <div className="absolute right-0 bottom-0 w-3 h-3 border-2 border-white rounded-full z-10" style={{ backgroundColor: "rgb(120, 222, 69)" }}></div>
                </div>

                <div className="flex-grow flex flex-col items-stretch justify-start ml-3 overflow-hidden">
                    <div className="flex">
                        <span className="block text-base text-triple38 font-semibold leading-6 overflow-hidden overflow-ellipsis whitespace-nowrap -my-1.5">
                            {
                                room.type == 'solo' &&
                                room.participants[0].user.name
                            }
                            {
                                room.type == 'direct' &&
                                room.participants.filter(p => p.user_id != authUser.id)[0].user.name
                            }
                            {
                                room.type == 'group' &&
                                room.name
                            }
                        </span>
                    </div>
                    <div className="flex flex-col items-stretch mt-2">
                        <span className="block text-xs text-triple142 leading-4 overflow-hidden overflow-ellipsis whitespace-nowrap" style={{ marginTop: '-2px', marginBottom: '-3px' }}>
                            Active now
                        </span>
                    </div>
                </div>
            </div>
        </h1>
    );
}
