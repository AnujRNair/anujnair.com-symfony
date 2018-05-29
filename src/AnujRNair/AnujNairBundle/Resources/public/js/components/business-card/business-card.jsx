import React, { Component } from 'react';

import './business-card.scss';

export default class BusinessCard extends Component {
  render() {
    return (
      <div className="business-card">
        <div className="business-card__name">Anuj Nair</div>
        <div className="business-card__profession">
          Senior Software Engineer, Front End Performance & Architecture{' '}
        </div>
        <ul className="business-card__details list">
          <li className="icon icon-location">
            San Francisco Bay Area, California
          </li>
          <li className="icon icon-calendar">2003 - Present</li>
          <li className="icon icon-spanner">Slack Technologies</li>
          <li className="icon icon-earth-full">http://anujnair.com</li>
        </ul>
      </div>
    );
  }
}
