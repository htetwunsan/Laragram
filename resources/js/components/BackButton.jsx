import React, { Component } from 'react';

export default class BackButton extends Component {

    constructor(props, context) {
        super(props, context);
    }

    render() {
        return (
            <button className="flex flex-row" tabIndex="0" type="button" onClick={this.props.handleClick}>
                <span className="block transform -rotate-90">
                    <svg aria-label="Back" className="text-triple38 fill-current w-6 h-6" role="img" viewBox="0 0 24 24">
                        <path d="M21 17.502a.997.997 0 01-.707-.293L12 8.913l-8.293 8.296a1 1 0 11-1.414-1.414l9-9.004a1.03 1.03 0 011.414 0l9 9.004A1 1 0 0121 17.502z">

                        </path>
                    </svg>
                </span>
            </button>
        );
    }
}
