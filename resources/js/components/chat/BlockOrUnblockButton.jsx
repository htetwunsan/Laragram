import React from 'react';

export default function BlockOrUnblockButton({ participant, handleShowBlockDialog, handleShowUnblockDialog }) {

    const handleClickBlock = e => {
        handleShowBlockDialog();
    };

    const handleClickUnblock = e => {
        handleShowUnblockDialog();
    };

    if (participant.user.is_blocked_by_auth_user) {
        return (
            <button className="flex flex-col items-stretch" type="button" onClick={handleClickUnblock}>
                <span className="block text-sm text-error text-left leading-18px"
                    style={{ marginTop: '-3px', marginBottom: '-4px' }}>
                    Unblock
                </span>
            </button>
        );
    }

    return (
        <button className="flex flex-col items-stretch" type="button" onClick={handleClickBlock}>
            <span className="block text-sm text-error text-left leading-18px"
                style={{ marginTop: '-3px', marginBottom: '-4px' }}>
                Block
            </span>
        </button>
    );

}
