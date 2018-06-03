// toolkit must be first
import '@anujnair/css/toolkit.scss';

import React from 'react';
import ReactDOM from 'react-dom';

import Error from '@anujnair/js/pages/error';

const mainContainer = document.querySelector('.main-content');
ReactDOM.render(React.createElement(Error, window.reactProps), mainContainer);
