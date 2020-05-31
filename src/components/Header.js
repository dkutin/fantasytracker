import React, { Component } from 'react';

class Header extends Component {
    render() {
        return (
            <header id="home">
                <div className="banner">
                    <h1>Fantasy Tracker</h1>
                    <p>A Yahoo! Fantasy based Application to track and display NBA trends</p>
                    <hr className="intro"/>
                </div>
            </header>
        );
    }
}

export default Header;
