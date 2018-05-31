import React, { Component } from 'react';
import PropTypes from 'prop-types';

import './pagination.scss';

export default class Pagination extends Component {
  static propTypes = {
    currentPage: PropTypes.number.isRequired,
    maxResults: PropTypes.number.isRequired,
    noPerPage: PropTypes.number.isRequired,
    slug: PropTypes.string.isRequired
  };

  shouldComponentUpdate() {
    return false;
  }

  renderOlder() {
    if (this.props.currentPage * this.props.noPerPage > this.props.maxResults) {
      return null;
    }

    const { slug, currentPage, noPerPage } = this.props;
    const newPage = currentPage + 1;

    return (
      <div className="pagination__button pagination__older">
        <a href={`${slug}?page=${newPage}&noPerPage=${noPerPage}`} rel="next">
          &larr; Older
        </a>
      </div>
    );
  }

  renderNewer() {
    if (this.props.currentPage <= 1) {
      return null;
    }

    const { slug, currentPage, noPerPage } = this.props;
    const newPage = currentPage - 1;

    return (
      <div className="pagination__button pagination__newer">
        <a href={`${slug}?page=${newPage}&noPerPage=${noPerPage}`} rel="prev">
          Newer &rarr;
        </a>
      </div>
    );
  }

  render() {
    return (
      <div className="pagination">
        {this.renderOlder()}
        {this.renderNewer()}
      </div>
    );
  }
}
