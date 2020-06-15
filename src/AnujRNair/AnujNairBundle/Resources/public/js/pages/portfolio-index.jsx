import React, { Component, Fragment } from 'react';

import { Tags, TagSummary as TagSummaryProps } from '@anujnair/js/types/tag';
import { Articles } from '@anujnair/js/types/article';

import Article from '@anujnair/js/components/article/article';
import TagSummary from '@anujnair/js/components/tag-summary/tag-summary';
import PropTypes from 'prop-types';

export default class PortfolioIndex extends Component {
  static propTypes = {
    articles: Articles.isRequired,
    tags: Tags.isRequired,
  };

  shouldComponentUpdate() {
    return false;
  }

  findTagsByArticle(article) {
    return article.tagIds.map((tagId) =>
      this.props.tags.find((tag) => tag.id === tagId)
    );
  }

  renderArticles() {
    if (this.props.articles.length === 0) {
      return <h2>No portfolio items currently exist</h2>;
    }

    return this.props.articles.map((article) => (
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
    tagSummary: TagSummaryProps.isRequired,
  };

  static defaultProps = {
    tagId: null,
  };

  shouldComponentUpdate() {
    return false;
  }

  renderAbout() {
    return (
      <Fragment>
        <h3>About</h3>
        <p>
          Listed here are the open source projects that I have developed over
          the years.
        </p>
        <p>
          My portfolio includes open source plugins, sites created in different
          frameworks, and helpful libraries. They are mostly listed on my GitHub
          page for reference.
        </p>
        <p>
          Click on any one of the projects to view more information on what it
          is.
        </p>
      </Fragment>
    );
  }

  renderLearnMore() {
    return (
      <Fragment>
        <h3>Learn More</h3>
        <ul className="list">
          <li className="icon icon-pencil icon--absolute">
            <a href="/blog">View my blog posts</a>
          </li>
          <li className="icon icon-man icon--absolute">
            <a href="/about">Learn more about me and contact me</a>
          </li>
        </ul>
      </Fragment>
    );
  }

  renderTagSummary() {
    return (
      <TagSummary
        header="Portfolio"
        highlightTagId={this.props.tagId}
        icon="icon-tag"
        list={this.props.tagSummary}
        urlPath="portfolio/t"
      />
    );
  }

  render() {
    return (
      <Fragment>
        {this.renderAbout()}
        {this.renderLearnMore()}
        {this.renderTagSummary()}
      </Fragment>
    );
  }
}
