import React, { Component, Fragment } from 'react';
import PropTypes from 'prop-types';

import { Similar } from '@anujnair/js/types/post';

export default class SimilarPosts extends Component {
  static propTypes = {
    icon: PropTypes.string,
    list: Similar,
    urlPath: PropTypes.string.isRequired
  };

  static defaultProps = {
    icon: null
  };

  renderSection(section) {
    if (!this.props.list[section] || this.props.list[section].length === 0) {
      return null;
    }

    const icon = this.props.icon
      ? `icon ${this.props.icon} icon--absolute`
      : '';

    const items = this.props.list[section].map(post => {
      return (
        <li key={post.id} className={icon}>
          <a href={`/${this.props.urlPath}/${post.id}-${post.urlTitle}`}>{post.title}</a>
        </li>
      );
    });

    return (
      <Fragment>
        <li>{section}</li>
        <ul>{items}</ul>
      </Fragment>
    );
  }

  render() {
    const keys = Object.keys(this.props.list);

    if (keys.every(key => this.props.list[key].length === 0)) {
      return null;
    }

    return (
      <Fragment>
        <h3>You may also be interested in &hellip;</h3>
        <ul className={'list'}>
          {this.renderSection('Extra Reading')}
          {this.renderSection('Similar Blog Posts')}
        </ul>
      </Fragment>
    );
  }
}
