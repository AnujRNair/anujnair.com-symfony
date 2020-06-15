// toolkit must be first
import '@anujnair/css/toolkit.scss';

import React from 'react';
import ReactDOM from 'react-dom';

import PortfolioArticle, {
  PortfolioAside,
} from '@anujnair/js/pages/portfolio-article';

const mainContainer = document.querySelector('.main-content');
ReactDOM.render(
  React.createElement(PortfolioArticle, window.reactProps),
  mainContainer
);

const asideContainer = document.querySelector('.aside-content');
ReactDOM.render(
  React.createElement(PortfolioAside, window.reactProps),
  asideContainer
);
