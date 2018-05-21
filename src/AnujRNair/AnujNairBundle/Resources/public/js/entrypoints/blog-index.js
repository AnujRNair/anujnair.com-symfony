// toolkit must be first
import '@anujnair/css/toolkit.scss';

import React from 'react';
import ReactDOM from 'react-dom';

import BlogIndex from '@anujnair/js/pages/blog-index';

const mainContainer = document.querySelector('.main-content');
ReactDOM.render(React.createElement(BlogIndex, window.reactProps), mainContainer);
