import React from 'react';

import './business-card.scss';

function BusinessCard() {
  return (
    <div className="business-card">
      <div className="business-card__name">Anuj Nair</div>
      <div className="business-card__profession">
        Sr. Staff Software Engineer, Front End Performance, Infrastructure, &
        Architecture{' '}
      </div>
      <ul className="business-card__details list">
        <li className="icon icon-location">
          San Francisco Bay Area, California
        </li>
        <li className="icon icon-calendar">2003 - Present</li>
        <li className="icon icon-spanner">Slack Technologies</li>
        <li className="icon icon-earth-full">https://anujnair.com</li>
      </ul>
    </div>
  );
}

export default BusinessCard;
