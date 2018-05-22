import React, { Component, Fragment } from 'react';
import PropTypes from 'prop-types';

import { Archive, Posts, TagSummary } from '@anujnair/js/types/blog';

import MultiDimensionalList from '@anujnair/js/components/multi-dimensional-list/multi-dimensional-list.jsx';

export default class BlogIndex extends Component {
  static propTypes = {
    page: PropTypes.number.isRequired,
    noPerPage: PropTypes.number.isRequired,
    posts: Posts,
    tagSummary: TagSummary
  };

  render() {
    return (<div />)
  }
}

export class BlogAside extends Component {
  static propTypes = {
    archive: Archive
  };

  renderArchive() {
    return (
      <Fragment>
        <h3>Past Blog Entries</h3>
        <MultiDimensionalList
          icon={'icon-calendar'}
          list={this.props.archive}
          urlPath={'blog'}
        />
      </Fragment>
    );
  }

  render() {
    return this.renderArchive();
  }
}
