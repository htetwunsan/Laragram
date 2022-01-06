import React from 'react';
import { useNavigate, useParams } from "react-router-dom";

export default function AddPeopleNextButton({ users }) {

    const { roomId } = useParams();
    const navigate = useNavigate();

    const handleClick = e => {
        // change
        axios.post('/api/rooms/' + roomId + '/participants', { user_ids: users.map(user => user.id) })
            .then(response => {
                navigate('/direct/r/' + roomId + '/details', { replace: true });
            })
            .catch(error => {
                showBottomeToast(error);
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
