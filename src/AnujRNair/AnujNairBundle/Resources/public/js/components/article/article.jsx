import React, { Component } from 'react';

import { Article as ArticleProps } from '@anujnair/js/types/article';
import { Tags } from '@anujnair/js/types/tag';

import './article.scss';

export default class Article extends Component {
  static propTypes = {
    article: ArticleProps,
    tags: Tags
  };

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
      <li className={'icon icon-tags'}>
        Tags:
        <ul className={'article__metadata-tags'}>{items}</ul>
      </li>
    );
  }

  renderMetaData() {
    return (
      <ul className={'article__metadata'}>
        <li className={'icon icon-calendar'}>
          Created: {this.props.article.dateCreated}
        </li>
        <li className={'icon icon-link'}>
          <a href={this.props.article.link}>{this.props.article.name}</a>
        </li>
        {this.renderTagsMetaData()}
      </ul>
    );
  }

  render() {
    return (
      <article className={'article'}>
        <h2 className={'article__title'}>
          <a
            href={`/portfolio/${this.props.article.id}-${
              this.props.article.urlTitle
            }`}
          >
            {this.props.article.name}
          </a>
        </h2>
        {this.renderMetaData()}
      </article>
    );
  }
}
