import React, { Component } from 'react';
import ProfileImage from '../ProfileImage';

export default class SearchResultUser extends Component {


    renderToggleSelection = (selected) => {
        if (selected) {
            return (
                <svg aria-label="Toggle selection" color="#0095f6" fill="#0095f6" height="24" role="img" viewBox="0 0 24 24" width="24">
                    <path d="M12.001.504a11.5 11.5 0 1011.5 11.5 11.513 11.513 0 00-11.5-11.5zm5.706 9.21l-6.5 6.495a1 1 0 01-1.414-.001l-3.5-3.503a1 1 0 111.414-1.414l2.794 2.796L16.293 8.3a1 1 0 011.414 1.415z">

                    </path>
                </svg>
            );
        }
        return (
            <svg aria-label="Toggle selection" color="#262626" fill="#262626" height="24" role="img" viewBox="0 0 24 24" width="24">
                <circle cx="12.008" cy="12" fill="none" r="11.25" stroke="currentColor" strokeLinejoin="round" strokeWidth="1.5"></circle>
            </svg>
        );
    }

    render() {
        const { user, selected = false, handleClick } = this.props;
        if (!user) return <></>;
        return (
            <div className="flex flex-col items-stretch" tabIndex="0" role="button" onClick={e => handleClick(e, user)}>
                <div className="flex flex-row items-center justify-start px-4 py-2">
                    <div className="flex items-center justify-center w-11 h-11 mr-3 select-none">
                        <ProfileImage url={user.profile_image} />
                    </div>

                    <div className="flex-grow flex flex-col items-stretch justify-center overflow-hidden">
                        <div className="flex">
                            <span className="block text-sm text-triple38 font-semibold leading-18px overflow-hidden overflow-ellipsis whitespace-nowrap select-none" style={{ marginTop: '-3px', marginBottom: '-4px' }}>
                                {user.username}
                            </span>
                        </div>

                        <div className="flex flex-col items-stretch mt-2">
                            <div className="flex flex-col items-stretch" style={{ marginTop: '-3px', marginBottom: '-4px' }}>
                                <div className="flex">
                                    <span className="block text-sm text-triple142 leading-18px overflow-hidden overflow-ellipsis whitespace-nowrap">
                                        <span className="select-none">
                                            {user.name}
                                        </span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div className="flex flex-col items-stretch ml-2">
                        <button className="flex items-center justify-center p-2" type="button">
                            <span className="block">
                                {this.renderToggleSelection(selected)}
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        );
    }
}
