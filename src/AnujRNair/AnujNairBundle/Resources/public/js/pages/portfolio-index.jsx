import React, { Component, Fragment } from 'react';

import { Tags, TagSummary as TagSummaryProps } from '@anujnair/js/types/tag';
import { Articles } from '@anujnair/js/types/article';

import Article from '@anujnair/js/components/article/article.jsx';
import TagSummary from '@anujnair/js/components/tag-summary/tag-summary.jsx';
import PropTypes from 'prop-types';

export default class PortfolioIndex extends Component {
  static propTypes = {
    articles: Articles,
    tags: Tags
  };

  findTagsByArticle(article) {
    return article.tagIds.map(tagId =>
      this.props.tags.find(tag => tag.id === tagId)
    );
  }

  renderArticles() {
    if (this.props.articles.length === 0) {
      return <h2>No portfolio items currently exist</h2>;
    }

    return this.props.articles.map(article => (
      <Article
        article={article}
        key={article.id}
        mini
        tags={this.findTagsByArticle(article)}
      />
    ));
  }

  render() {
    return (
      <Fragment>
        <h2>My Portfolio &amp; Development Work</h2>
        {this.renderArticles()}
      </Fragment>
    );
  }
}

export class PortfolioAside extends Component {
  static propTypes = {
    tagId: PropTypes.number,
    tagSummary: TagSummaryProps
  };

  static defaultProps = {
    tagId: null
  };

  renderTagSummary() {
    return (
      <Fragment>
        <h3>Portfolio Tags</h3>
        <TagSummary
          highlightTagId={this.props.tagId}
          icon={'icon-tag'}
          list={this.props.tagSummary}
          urlPath={'portfolio/t'}
        />
      </Fragment>
    );
  }

  render() {
    return (
      <Fragment>
        {this.renderTagSummary()}
      </Fragment>
    );
  }
}
