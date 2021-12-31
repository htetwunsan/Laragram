import React, { useState, useEffect, useContext } from 'react';
import { useNavigate, useParams } from "react-router-dom";
import BackButton from '../BackButton';
import NavHeading from "../NavHeading";
import ProfileImage from '../ProfileImage';
import TopNavigation from '../TopNavigation';
import { AppContext } from "./AppContext";

export default function ChatDetails() {

    const { roomId } = useParams();
    const navigate = useNavigate();
    const { authUser } = useContext(AppContext);
    const [room, setRoom] = useState(null);

    useEffect(() => {
        axios.get('/api/rooms/' + roomId).then(response => {
            setRoom(response.data);
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
            navigate('/direct/inbox');
        });
    };

    const handleClickBlock = e => {
        console.log(e);
    };

    const handleClickReport = e => {
        console.log(e);
    };

    const isMuted = () => {
        if (!room) return false;
        if (!room.participants.find(e => e.user_id == authUser.id).room_muted_at) return false;
        return true;
    };


    return (
        <section id="section_main" className="flex-grow flex flex-col items-stretch overflow-hidden">
            <TopNavigation
                left={<BackButton handleClick={e => navigate(-1)} />}
                center={<NavHeading name="Details" />}
                right={<div className="flex flex-col items-stretch w-8 h-6"></div>} />

            {(() => {
                if (!room) return null;
                let { participants } = room;
                if (room.type != 'solo') {
                    participants = participants.filter(p => p.user_id != authUser.id);
                }

                return (
                    <main className="bg-white flex-grow flex flex-col items-stretch overflow-y-auto overflow-x-hidden" role="main">
                        <div className="flex flex-col items-stretch p-4 pt-5 border-b border-triple219">
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
                                <div className="flex flex-col items-stretch px-4 my-2">
                                    <div className="flex flex-col items-stretch">
                                        <h4 className="text-base text-triple38 font-semibold leading-6 -my-1.5">Members</h4>
                                    </div>
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
                                                            <div className="flex flex-col items-stretch" style={{ marginTop: '-3px', marginBottom: '-4px' }}>
                                                                <span className="block text-sm text-triple142 leading-18px overflow-hidden overflow-ellipsis whitespace-nowrap">
                                                                    {user.name}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        );
                                    })}

                            </div>
                        </div>

                        <div className="flex flex-col items-stretch border-b border-triple219">
                            <div className="flex flex-col items-stretch m-4">
                                <button className="flex flex-col items-stretch" type="button" onClick={handleClickDeleteChat}>
                                    <span className="block text-sm text-error text-left leading-18px" style={{ marginTop: '-3px', marginBottom: '-4px' }}>
                                        Delete Chat
                                    </span>
                                </button>
                            </div>

                            <div className="flex flex-col items-stretch m-4">
                                <button className="flex flex-col items-stretch" type="button" onClick={handleClickBlock}>
                                    <span className="block text-sm text-error text-left leading-18px" style={{ marginTop: '-3px', marginBottom: '-4px' }}>
                                        Block
                                    </span>
                                </button>
                            </div>

                            <div className="flex flex-col items-stretch m-4">
                                <button className="flex flex-col items-stretch" type="button" onClick={handleClickReport}>
                                    <span className="block text-sm text-error text-left leading-18px" style={{ marginTop: '-3px', marginBottom: '-4px' }}>
                                        Report
                                    </span>
                                </button>
                            </div>
                        </div>
                    </main>
                );
            })()}
        </section>
    );
}
