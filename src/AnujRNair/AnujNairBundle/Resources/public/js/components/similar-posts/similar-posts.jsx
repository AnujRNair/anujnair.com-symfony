import React, { Component, Fragment } from 'react';
import PropTypes from 'prop-types';

import { Similar } from '@anujnair/js/types/post';

export default class SimilarPosts extends Component {
  static propTypes = {
    icon: PropTypes.string,
    list: Similar.isRequired,
    urlPath: PropTypes.string.isRequired
  };

  static defaultProps = {
    icon: null
  };

  shouldComponentUpdate() {
    return false;
  }

  renderSection(section) {
    if (!this.props.list[section] || this.props.list[section].length === 0) {
      return null;
    }

    const icon = this.props.icon
      ? `icon ${this.props.icon} icon--absolute`
      : '';

    const items = this.props.list[section].map(post => (
      <li className={icon} key={post.id}>
        <a href={`/${this.props.urlPath}/${post.id}-${post.urlTitle}`}>
          {post.title || post.name}
        </a>
      </li>
    ));

    return (
      <Fragment key={section}>
        <li>
          {section}
          <ul>{items}</ul>
        </li>
      </Fragment>
    );
  }

  render() {
    const keys = Object.keys(this.props.list);

    if (keys.every(key => this.props.list[key].length === 0)) {
      return null;
    }

    const sections = keys.map(key => this.renderSection(key));

    return (
      <Fragment>
        <h3>You may also be interested in &hellip;</h3>
        <ul className="list">{sections}</ul>
      </Fragment>
    );
  }
}
