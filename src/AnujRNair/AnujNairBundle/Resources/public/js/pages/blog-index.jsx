import React, { Component, Fragment } from 'react';
import PropTypes from 'prop-types';

import { Archive, Posts, TagSummary as TagSummaryProps } from '@anujnair/js/types/blog';

import MultiDimensionalList from '@anujnair/js/components/multi-dimensional-list/multi-dimensional-list.jsx';
import TagSummary from '@anujnair/js/components/tag-summary/tag-summary.jsx';

export default class BlogIndex extends Component {
  static propTypes = {
    page: PropTypes.number.isRequired,
    noPerPage: PropTypes.number.isRequired,
    posts: Posts
  };

  render() {
    return (
      <Fragment>
        <h2>This is a blog title</h2>
        <p>This is some text</p>
      </Fragment>
    );
  }
}

export class BlogAside extends Component {
  static propTypes = {
    archive: Archive,
    tagSummary: TagSummaryProps
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

  renderTagSummary() {
    return (
      <Fragment>
        <h3>Blog Tags</h3>
        <TagSummary
          icon={'icon-tag'}
          list={this.props.tagSummary}
          urlPath={'blog/t'}
        />
      </Fragment>
    );
  }

  render() {
    return (
      <Fragment>
        {this.renderArchive()}
        {this.renderTagSummary()}
      </Fragment>
    );
  }
}
