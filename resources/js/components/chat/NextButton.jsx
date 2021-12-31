import React from 'react';
import { useNavigate } from "react-router-dom";

export default function NextButton({ users }) {

    const navigate = useNavigate();

    const handleClick = e => {
        axios.post('/api/rooms', { user_ids: users.map(user => user.id) })
            .then(response => {
                const room = response.data;
                navigate('/direct/r/' + room.id);
            }, error => {
                console.log(error);
                // showBottomToast(error);
            });
    }

    if (users.length > 0) {
        return (
            <button
                className="text-sm text-fb_blue text-center font-semibold leading-18px"
                type="button" onClick={handleClick}>
                <span className="block" style={{ padding: '7px 9px' }}>Next</span>
            </button>
        );
    }

    return (
        <button
            className="text-sm text-fb_blue text-opacity-30 text-center font-semibold leading-18px"
            type="button"
            disabled>
            <span className="block" style={{ padding: '7px 9px' }}>Next</span>
        </button>
    );
}
