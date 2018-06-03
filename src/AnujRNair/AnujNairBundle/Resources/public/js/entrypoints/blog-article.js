// toolkit must be first
import '@anujnair/css/toolkit.scss';

import React from 'react';
import ReactDOM from 'react-dom';

import BlogArticle, { BlogAside } from '@anujnair/js/pages/blog-article';

const mainContainer = document.querySelector('.main-content');
ReactDOM.render(
  React.createElement(BlogArticle, window.reactProps),
  mainContainer
);

const asideContainer = document.querySelector('.aside-content');
ReactDOM.render(
  React.createElement(BlogAside, window.reactProps),
  asideContainer
);
