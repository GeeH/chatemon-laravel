import React, {Component} from 'react';
import ReactDOM from 'react-dom';
import Combat from "./Combat";

export default class Index extends Component {
    render() {
        return (
            <div className="container is-fluid">
                <Combat/>
            </div>
        );
    }
}

if (document.getElementById('index')) {
    ReactDOM.render(<Index/>, document.getElementById('index'));
}
