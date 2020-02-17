import React, {Component} from 'react';
import ReactDOM from 'react-dom';
import Combat from "./Combat";

export default class Index extends Component {
    render() {
        return (
            <div className="container is-fluid">
                <div className="notification is-success is-size-1 is-full-width columns">
                    <div className="column is-two-fifths">
                        <p><strong>Chatemon</strong></p>
                        <p>Gotta Chat Em All</p>
                    </div>
                    <div className="column is-1">
                        <i className="fas fa-3x fa-mobile-alt"/>
                    </div>
                    <div className="column">
                        <p>Send an SMS to: <strong>+44 7723 501858</strong></p>
                        <p> with the move you wish to play (A, B or C)</p>
                    </div>
                </div>
                <Combat/>
            </div>
        );
    }
}

if (document.getElementById('index')) {
    ReactDOM.render(<Index/>, document.getElementById('index'));
}
