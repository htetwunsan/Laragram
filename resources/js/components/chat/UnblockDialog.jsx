import React, { useState, useEffect } from 'react';
import clsx from 'clsx';

export default function UnblockDialog({ participant, handleClickUnblock, hideDialog }) {

    const [animate, setAnimate] = useState(false);

    useEffect(() => {
        setAnimate(true);
    }, []);

    const handleClickCancel = e => {
        hideDialog();
    };

    return (
        <div
            className={clsx(
                'bg-black', 'bg-opacity-65', 'absolute', 'inset-0', 'flex', 'flex-col', 'items-center', 'justify-around',
                'z-1000', !animate && 'dialog-container', animate && 'dialog-container-active'
            )}>
            <div
                className={clsx(
                    'bg-white', 'flex', 'flex-col', 'items-stretch', 'justify-center', 'm-5', !animate && 'dialog', animate && 'dialog-active'
                )}
                style={{ width: '260px', borderRadius: '12px' }}>
                <div className="flex flex-col items-stretch justify-center mx-8 mt-8 mb-4">
                    <h3 className="text-lg text-triple38 text-center font-semibold leading-6 -mt-1 -mb-1.5">
                        Unblock {participant.user.username}?</h3>
                    <div className="text-sm text-triple142 text-center leading-18px pt-4 -mb-1" style={{ marginTop: '-3px' }}>
                        They will now be able to see your posts and follow you on Instagram. Instagram won't let them known you
                        unblocked them.
                    </div>
                </div>
                <button className="flex-grow text-sm text-error text-center font-bold h-12 py-1 px-2 border-b border-t border-triple219"
                    type="button"
                    tabIndex="0"
                    onClick={e => handleClickUnblock(e, participant)}>
                    Unblock
                </button>
                <button className="text-sm text-triple38 text-center font-bold h-12 py-1 px-2"
                    type="button"
                    tabIndex="0"
                    onClick={handleClickCancel}>
                    Cancel
                </button>
            </div>
        </div>

    );
}
