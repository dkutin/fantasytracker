import React, {Component} from "react";

class Player extends Component {

    render() {
        if (this.props.highlight) {
            var highlight = this.props.highlight;
        }
    
        if (this.props.info) {
            var name = this.props.info.full_name;
            var image = this.props.info.image;
            var player_id = this.props.player_id;
        }
    
        if (this.props.count) {
            var count = this.props.count;
        }

        return (
                <div key={player_id} className="card" onClick={this.props.handleClick} >
                    <div className="content front">
                    <div className="rank">
                        <p>{count}.</p>
                    </div>
                    <img className="player-image" id={highlight} alt={name} src={image}/> <br/>
                    <span className="player-name">{name}</span>                    
                </div>
                </div>
        );
    }

}

export default Player;