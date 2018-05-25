import React, { Component } from 'react';
import PropTypes from 'prop-types';

import { TagSummary as TagSummaryProps } from '@anujnair/js/types/tag';

import './tag-summary.scss';

export default class TagSummary extends Component {
  static propTypes = {
    highlightTagId: PropTypes.number,
    icon: PropTypes.string,
    list: TagSummaryProps,
    urlPath: PropTypes.string.isRequired
  };

  static defaultProps = {
    highlightTagId: null,
    icon: null
  };

  render() {
    if (Object.keys(this.props.list).length === 0) {
      return null;
    }

    const icon = this.props.icon ? `icon ${this.props.icon}` : '';
    const items = Object.keys(this.props.list).map(idx => {
      const item = this.props.list[idx];
      const highlightClass =
        this.props.highlightTagId === item.id ? 'tag-summary--highlighted' : '';

      return (
        <li key={idx} className={`${icon} ${highlightClass}`}>
          <a
            href={`/${this.props.urlPath}/${
              item.id
            }-${item.name.toLowerCase().replace(' ', '-')}`}
          >
            {item.name}
            <em>({item.tagCount})</em>
          </a>
        </li>
      );
    });

    return <ul className={'tag-summary'}>{items}</ul>;
  }
}
