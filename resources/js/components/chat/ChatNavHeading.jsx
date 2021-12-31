import React, { useContext } from 'react';
import ProfileImage from '../ProfileImage';
import { AppContext } from './AppContext';

export default function ChatNavHeading({ room }) {
    if (!room) return null;

    const context = useContext(AppContext);

    let user;

    if (room.type == 'solo') {
        user = room.participants[0].user;
    } else {
        user = room.participants.filter(e => e.user_id != context.authUser.id)[0].user;
    }

    return (
        <h1 className="flex-auto flex flex-col items-stretch">
            <div className="flex items-center">
                <div className="flex items-center justify-center w-6 h-6 rounded-full relative">
                    <ProfileImage url={user.profile_image} />

                    <div className="absolute left-3 top-3 w-3 h-3 border-2 border-white rounded-full mt-0.5 -mr-0.5 -mb-0.5 ml-0.5 z-10" style={{ backgroundColor: "rgb(120, 222, 69)" }}></div>
                </div>

                <div className="flex-grow flex flex-col items-stretch justify-start ml-3">
                    <div className="flex flex-col items-stretch">
                        <span className="block text-base text-triple38 font-semibold leading-6 overflow-hidden overflow-ellipsis whitespace-nowrap -my-1.5">
                            {user.name}
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
