// toolkit must be first
import '@anujnair/css/toolkit.scss';

import React from 'react';
import ReactDOM from 'react-dom';

import AboutIndex, { AboutAside } from '@anujnair/js/pages/about-index';

const mainContainer = document.querySelector('.main-content');
ReactDOM.render(
  React.createElement(AboutIndex, window.reactProps),
  mainContainer
);

const asideContainer = document.querySelector('.aside-content');
ReactDOM.render(
  React.createElement(AboutAside, window.reactProps),
  asideContainer
);
