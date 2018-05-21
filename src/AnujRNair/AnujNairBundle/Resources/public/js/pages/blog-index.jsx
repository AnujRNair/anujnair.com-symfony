import React, { Component } from 'react';
import PropTypes from 'prop-types';

export default class BlogIndex extends Component {
  static propTypes = {
    page: PropTypes.number.isRequired,
    noPerPage: PropTypes.number.isRequired,
    blogPosts: PropTypes.any,
    archive: PropTypes.any,
    tagSummary: PropTypes.any
  };

  render() {
    return <div>Hello</div>;
  }
}
