import React, {Component} from 'react';

export default class Combatant extends Component {
    render() {
        return (
            <section className={'hero is-' + this.props.color}>
                <div className="hero-body">
                    <div className="columns">
                        <div className="column is-1">
                            <i className={'fas fa-3x fa-' + this.props.icon}/>
                        </div>
                        <div className="row columns column">
                            <div className="column has-text-left is-6">
                                <h3 className="title">
                                    {this.props.combatant.name}
                                </h3>
                            </div>
                            <div className="column has-text-right is-6">
                                <h3 className="title">
                                    Lv. {this.props.combatant.level}
                                </h3>
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
