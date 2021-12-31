import React, { Component } from 'react';


export default class TopNavigation extends Component {

    constructor(props, context) {
        super(props, context);
    }

    render() {
        return (
            <nav className="flex flex-col items-stretch order-first z-50">
                <div className="flex flex-col items-stretch h-11">
                    <header
                        className="max-w-screen-sm mx-auto fixed top-0 left-0 right-0 flex flex-col items-stretch border-b border-solid border-triple219 z-10 bg-white">
                        <div className="flex flex-row items-center justify-between h-11 px-4">
                            <div className="flex items-center justify-start w-8">
                                {this.props.left}
                            </div>

                            {this.props.center}

                            <div className="flex items-center justify-end">
                                {this.props.right}
                            </div>
                        </div>
                    </header>
                </div>
            </nav>
        );
    }
}
