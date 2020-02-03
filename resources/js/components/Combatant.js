import React, {Component} from 'react';

export default class Combatant extends Component {
    render() {
        return (
            <section className={'hero is-' + this.props.color}>
                <div className="hero-body">
                    <div className="columns">
                        <div className="column">
                            <div className="container is-fluid has-text-left">
                                <h3 className="title">
                                    {this.props.combatant.name}
                                </h3>
                            </div>
                        </div>
                        <div className="column">
                            <div className="container is-fluid has-text-right">
                                <h3 className="title">
                                    Lv. {this.props.combatant.level}
                                </h3>
                            </div>
                        </div>
                    </div>

                    <progress className="progress is-large" value={this.props.combatant.health}
                              max={this.props.combatant.maxHealth}>
                    </progress>
                </div>
            </section>
        );
    }
}
