import React, { Component, Fragment } from 'react';
import PropTypes from 'prop-types';
import classNames from 'classnames';

import { Article as ArticleProps } from '@anujnair/js/types/article';
import { Tags } from '@anujnair/js/types/tag';

import './article.scss';

export default class Article extends Component {
  static propTypes = {
    article: ArticleProps.isRequired,
    mini: PropTypes.bool,
    tags: Tags.isRequired
  };

  static defaultProps = {
    mini: false
  };

  shouldComponentUpdate() {
    return false;
  }

  renderHeader() {
    if (this.props.mini === true) {
      return null;
    }

    return (
      <h2 className="article__header">
        <a
          href={`/portfolio/${this.props.article.id}-${
            this.props.article.urlTitle
          }`}
        >
          {this.props.article.name}
        </a>
      </h2>
    );
  }

  renderTitle() {
    if (this.props.mini === false) {
      return null;
    }

    return (
      <h2 className="article__title">
        <a
          href={`/portfolio/${this.props.article.id}-${
            this.props.article.urlTitle
          }`}
        >
          {this.props.article.name}
        </a>
      </h2>
    );
  }

  renderImage() {
    return (
      <a
        className="article__img"
        href={`/portfolio/${this.props.article.id}-${
          this.props.article.urlTitle
        }`}
      >
        <img alt={this.props.article.title} src={this.props.article.image} />
      </a>
    );
  }

  renderTagsMetaData() {
    if (this.props.tags.length === 0) {
      return null;
    }

    const items = this.props.tags.map(tag => (
      <li key={tag.id}>
        <a href={`/portfolio/t/${tag.id}-${tag.urlName}`}>{tag.name}</a>
      </li>
    ));

    return (
      <li className="icon icon-tags">
        Tags:
        <ul className="article__metadata-tags">{items}</ul>
      </li>
    );
  }

  renderMetaData() {
    return (
      <ul className="article__metadata">
        <li className="icon icon-calendar">
          Created: {this.props.article.dateCreated}
        </li>
        <li className="icon icon-link">
          <a href={this.props.article.link}>{this.props.article.name}</a>
        </li>
        {this.renderTagsMetaData()}
      </ul>
    );
  }

  renderContents() {
    if (this.props.mini === true) {
      return null;
    }

    return (
      <p
        className="article__contents"
        dangerouslySetInnerHTML={{ __html: this.props.article.contents }} // eslint-disable-line react/no-danger
      />
    );
  }

  render() {
    const classes = classNames({
      article: true,
      'article--mini': this.props.mini
    });

    return (
      <Fragment>
        {this.renderHeader()}
        <article className={classes}>
          {this.renderImage()}
          {this.renderTitle()}
          {this.renderMetaData()}
          {this.renderContents()}
        </article>
      </Fragment>
    );
  }
}
