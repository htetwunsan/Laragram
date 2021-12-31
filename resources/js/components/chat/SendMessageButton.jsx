import React from 'react';

export default function SendMessageButton({ handleClick }) {
    return (
        <button className="flex items-center justify-center text-sm text-fb_blue text-center font-semibold overflow-ellipsis mr-2"
            style={{ height: '42px' }}
            onClick={handleClick}
            type="button">
            Send
        </button>
    );
}
