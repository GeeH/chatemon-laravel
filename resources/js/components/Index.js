import React, {Component} from 'react';
import ReactDOM from 'react-dom';
import Combat from "./Combat";

export default class Index extends Component {
    render() {
        return (
            <Combat />
        );
    }
}

if (document.getElementById('index')) {
    console.log('found index');
    ReactDOM.render(<Index/>, document.getElementById('index'));
}
