import React, {Component} from 'react';

export default class Moves extends Component {
    render() {
        const buttons = this.props.moves.map((move, index) => {
            return (
                <button key={index} className="button is-fullwidth is-large is-info"
                        onClick={(e) => this.props.handleClick(index, e)}>
                    <h1 className="column is-one-fifth">{(index + 10).toString(36).toUpperCase()}</h1>
                    <h1 className="column"><strong>{move.name}</strong></h1>
                    <h5 className="column is-one-fifth">{move.accuracy}% - {move.damage}</h5>
                </button>
            );
        });

        return (
            <div className="buttons is-fullwidth">
                {buttons}
            </div>
        );
    }
}
