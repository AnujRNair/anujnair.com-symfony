// toolkit must be first
import '@anujnair/css/toolkit.scss';

import React from 'react';
import ReactDOM from 'react-dom';

import BlogIndex, { BlogAside } from '@anujnair/js/pages/blog-index';

const mainContainer = document.querySelector('.main-content');
ReactDOM.render(
  React.createElement(BlogIndex, window.reactProps),
  mainContainer
);

const asideContainer = document.querySelector('.aside-content');
ReactDOM.render(
  React.createElement(BlogAside, window.reactProps),
  asideContainer
);
