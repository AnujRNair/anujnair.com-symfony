import React, { Component } from 'react';
import { Tags, TagSummary as TagSummaryProps } from '@anujnair/js/types/tag';
import { Articles } from '@anujnair/js/types/articles';

export default class PortfolioIndex extends Component {
  static propTypes = {
    articles: Articles,
    tags: Tags
  };

  render() {
    return <div />;
  }
}

export class PortfolioAside extends Component {
  static propTypes = {
    tagSummary: TagSummaryProps
  };

  render() {
    return <div />;
  }
}
