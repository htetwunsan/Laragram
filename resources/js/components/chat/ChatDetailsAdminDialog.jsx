import React, { useState, useEffect } from 'react';
import ButtonWithIndicator from "./ButtonWithIndicator";
import clsx from 'clsx';

export default function ChatDetailsAdminDialog({ participant, handleClickRemoveFromGroup, handleClickMakeAdmin, handleClickRemoveAsAdmin, hideDialog }) {
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
                <ButtonWithIndicator className="flex-grow text-sm text-error text-center font-bold h-12 py-1 px-2 border-b border-triple219"
                    type="button"
                    tabIndex="0"
                    onClick={e => handleClickRemoveFromGroup(e, participant)}>
                    Remove From Group
                </ButtonWithIndicator>
                {
                    !participant.is_admin &&
                    <ButtonWithIndicator className="flex-grow text-sm text-triple38 text-center font-bold h-12 py-1 px-2 border-b border-triple219"
                        type="button"
                        tabIndex="0"
                        onClick={e => handleClickMakeAdmin(e, participant)}>
                        Make Admin
                    </ButtonWithIndicator>
                }
                {
                    participant.is_admin &&
                    <ButtonWithIndicator className="flex-grow text-sm text-triple38 text-center font-bold h-12 py-1 px-2 border-b border-triple219"
                        type="button"
                        tabIndex="0"
                        onClick={e => handleClickRemoveAsAdmin(e, participant)}>
                        Remove as Admin
                    </ButtonWithIndicator>
                }
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
