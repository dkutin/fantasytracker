import React, { Component } from 'react';

class Header extends Component {
    render() {
        return (
            <header id="home">
                <div className="banner">
                    <h1>Fantasy Tracker</h1>
                    <p>A Yahoo! Fantasy based Application to track and display NBA trends, while a work in progress, this application has big goals in mind wiht user functions to log in and see personalized stats for your fantasy league.</p>
                </div>
                
                {/* <div id="menu">
                    <button onclick="toggleMenu()">Hide/Show</button>
                    <span> This is some content that I want to stick</span>
                    <span></span>
                    <span></span>
                </div> */}
            </header>
        );
    }
}

export default Header;
