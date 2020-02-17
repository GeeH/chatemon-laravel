import React, {Component} from 'react';

export default class Moves extends Component {
    render() {
        const buttons = this.props.moves.map((move, index) => {
            return (
                <div key={index} className="button is-fullwidth is-large is-info">
                    <h1 className="column is-one-fifth">{(index + 10).toString(36).toUpperCase()}</h1>
                    <h1 className="column"><strong>{move.name}</strong></h1>
                    <h5 className="column is-one-fifth">{move.accuracy}% - {move.damage}</h5>
                </div>
            );
        });

        return (
            <div className="buttons is-fullwidth">
                {buttons}
            </div>
        );
    }
}
