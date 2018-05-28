import React, { Component, Fragment } from 'react';
import BusinessCard from '@anujnair/js/components/business-card/business-card';

import './jumbotron.scss';

export default class Jumbotron extends Component {
  render() {
    return (
      <div className={'jumbotron'}>
        <h1>This is Anuj Nair &rarr;</h1>
        <h2>This is his bio &darr;</h2>
        <BusinessCard />
      </div>
    );
  }
}
