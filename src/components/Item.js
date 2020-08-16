import React, {Component} from "react";
import Stats from './Stats';
import Player from './Player';
import ReactCardFlip from 'react-card-flip';

class Item extends Component {
    constructor() {
        super();
        this.state = {
            isFlipped: false
          };
        this.handleClick = this.handleClick.bind(this);
    }

    handleClick(e) {
        e.preventDefault();
        this.setState(prevState => ({ isFlipped: !prevState.isFlipped }));
    }

    render() {

        return (
            <div className="three-col">
            <ReactCardFlip isFlipped={this.state.isFlipped}
            flipSpeedFrontToBack={0.5}
            flipSpeedBackToFront={0.5}
            flipDirection="vertical">
                {/* TODO: Only pass one prop with a combination */}
                <Player key={this.props.info.player_id} highlight={this.props.highlight} info={this.props.info} count={this.props.count} handleClick={this.handleClick} />

                <Stats player_id={this.props.info.player_id} stats={this.props.stats} analysis={this.props.analysis}  handleClick={this.handleClick} />

            </ReactCardFlip>
            </div>
        );
    }

}

export default Item;