import React, {Component} from "react";

class Stats extends Component {

    render() {

        if (this.props.stats) {
            var playerStats = this.props.stats.map(function (stat) {
                return (
                    <tr>
                        <td>{stat.week}</td>
                        <td>{stat.pts}</td>
                        <td>{stat.ast}</td>
                        <td>{stat.reb}</td>
                        <td>{stat.blk}</td>
                        <td>{stat.stl}</td>
                        <td>{stat.trn}</td>
                    </tr>
                    )
            });
        }

        if (this.props.analysis) {
            // var analysis = this.props.analysis;

        }


        return (
            <div onClick={this.props.handleClick} className="card">
                <div className="content">
                <div className="player-analysis">
                    <div className="analysis-entry">
                        <span className="analysis">
                            <strong> One Week Avg</strong>: {this.props.analysis[1]}
                        </span>
                        <br/>
                    </div>
                    <div className="analysis-entry">
                        <span className="analysis">
                            <strong> Two Week Avg</strong>: {this.props.analysis[2]}
                        </span>
                        <br/>
                    </div>
                    <div className="analysis-entry">
                        <span className="analysis">
                            <strong> One Month Avg</strong>: {this.props.analysis[4]}
                        </span>
                        <br/>
                    </div>
                </div>
                <table className="player-stats">
                    <thead>
                    <tr>
                        <th>Week</th>
                        <th>Pts</th>
                        <th>Ast</th>
                        <th>Reb</th>
                        <th>Blk</th>
                        <th>Stl</th>
                        <th>TO</th>
                    </tr>
                    </thead>
                    <tbody>
                        {playerStats}
                    </tbody>
                </table>
                
                </div>
            </div>);
    }
}

export default Stats;

