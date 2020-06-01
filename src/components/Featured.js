import React, {Component} from "react";

class Featured extends Component {
    render() {

        if (this.props.data) {
            console.log(this.props.data);
            var players = this.props.data.players.map(function(player){
                var info = player.info;
                var analysis = player.analysis;
                var highlight = '';

                // Check that all values are set.
                if (typeof analysis[1] == 'undefined') analysis[1] = 0;
                if (typeof analysis[2] == 'undefined') analysis[2] = 0;
                if (typeof analysis[4] == 'undefined') analysis[4] = 0;

                if (analysis[1] >= analysis[2]) {
                    highlight = 'green';
                } else if (analysis[4] >= analysis[1] &&
                    analysis[4] >= analysis[2]) {
                    highlight = 'red';
                } else {
                    highlight = 'yellow';
                }
                return <div key={info.player_id} className="six-col player-item">
                    <div className="player-image" id={highlight}>
                        <img alt={info.full_name} src={info.image}/> <br/>
                        <span className="player-name"> {info.full_name}</span>
                    </div>
                    <div className="player-info">
                        <div className="player-analysis">
                            <span className="analysis">
                                One Week: {analysis[1]}
                            </span><br/>
                            <span className="analysis">
                                Two Weeks: {analysis[2]}
                            </span><br/>
                            <span className="analysis">
                                One Month: {analysis[4]}
                            </span><br/>
                        </div>
                    </div>
                </div>
            })
        }
        return (
            <section>
            <div className="featured-content">
                <div className="row">
                    <div className="two-col">
                        <h2>Top Fantasy Players</h2>
                        <div className="three-col">
                            <p>3 Col</p>
                        </div>
                        <div className="three-col">
                            <p>3 Col</p>
                        </div>
                        <div className="three-col">
                            <p>3 Col</p>
                        </div>
                    </div>
                    <div className="two-col">
                        <h2>Top Fantasy Players in Your League</h2>
                        <div className="three-col">
                            <p>3 Col</p>
                        </div>
                        <div className="three-col">
                            <p>3 Col</p>
                        </div>
                        <div className="three-col">
                            <p>3 Col</p>
                        </div>
                    </div>
                </div>
                <div className="row">
                    <div>
                        <h2> All Fantasy Players</h2>
                        {players}
                    </div>
                </div>
            </div>
            </section>
        );
    }
}

export default Featured;
