import React, { Component, Fragment } from 'react';
import PropTypes from 'prop-types';

import { Archive } from '@anujnair/js/types/post';

export default class BlogArchive extends Component {
  static propTypes = {
    icon: PropTypes.string,
    list: Archive.isRequired,
    urlPath: PropTypes.string.isRequired
  };

  static defaultProps = {
    icon: null
  };

  shouldComponentUpdate() {
    return false;
  }

  renderList() {
    const firstKeys = Object.keys(this.props.list).sort((a, b) => b - a);
    const icon = this.props.icon
      ? `icon ${this.props.icon} icon--absolute`
      : '';

    return firstKeys.map(f => {
      const secondKeys = Object.keys(this.props.list[f]);
      const second = secondKeys.map(s => {
        const items = this.props.list[f][s].map(item => (
          <li className={icon} key={item.id}>
            <a href={`/${this.props.urlPath}/${item.id}-${item.urlTitle}`}>
              {item.title}
            </a>
          </li>
        ));

        return (
          <ul key={s}>
            <li>{s}</li>
            <ul>{items}</ul>
          </ul>
        );
      });

      return (
        <ul className="list" key={f}>
          <li>{f}</li>
          {second}
        </ul>
      );
    });
  }

  render() {
    if (Object.keys(this.props.list).length === 0) {
      return null;
    }

    return (
      <Fragment>
        <h3>Past Blog Entries</h3>
        {this.renderList()}
      </Fragment>
    );
  }
}
