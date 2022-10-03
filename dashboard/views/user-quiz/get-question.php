<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<div class="flex-layout__large">
    <div class="box-view box-view--space box-flex">
        <div class="box-view__head margin-bottom-8">
            <h4 class="margin-bottom-2">Q1. What is the correct way to write a JavaScript array?</h4>
            <small class="style-italic">Marks: 10</small>
        </div>
        <div class="box-view__body">
            <div class="option-list">
                <label class="option">
                    <input type="radio" name="1" class="option__input">
                    <span class="option__item">
                        <span class="option__icon">
                            <svg class="icon-correct" xmlns="https://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <path d="M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10zm-.997-6l7.07-7.071-1.414-1.414-5.656 5.657-2.829-2.829-1.414 1.414L11.003 16z" />
                            </svg>
                            <svg class="icon-incorrect" xmlns="https://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <path d="M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10zm0-11.414L9.172 7.757 7.757 9.172 10.586 12l-2.829 2.828 1.415 1.415L12 13.414l2.828 2.829 1.415-1.415L13.414 12l2.829-2.828-1.415-1.415L12 10.586z" />
                            </svg>
                        </span>
                        <span class="option__value"> var colors = (1:"red", 2:"green", 3:"blue")</span>
                    </span>
                </label>
                <label class="option">
                    <input type="radio" name="1" class="option__input" checked>
                    <span class="option__item">
                        <span class="option__icon">
                            <svg class="icon-correct" xmlns="https://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <path d="M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10zm-.997-6l7.07-7.071-1.414-1.414-5.656 5.657-2.829-2.829-1.414 1.414L11.003 16z" />
                            </svg>
                            <svg class="icon-incorrect" xmlns="https://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <path d="M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10zm0-11.414L9.172 7.757 7.757 9.172 10.586 12l-2.829 2.828 1.415 1.415L12 13.414l2.828 2.829 1.415-1.415L13.414 12l2.829-2.828-1.415-1.415L12 10.586z" />
                            </svg>
                        </span>
                        <span class="option__value"> var colors = 1 = ("red"), 2 = ("green"), 3 = ("blue")</span>
                    </span>
                </label>
                <label class="option">
                    <input type="radio" name="1" class="option__input">
                    <span class="option__item">
                        <span class="option__icon">
                            <svg class="icon-correct" xmlns="https://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <path d="M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10zm-.997-6l7.07-7.071-1.414-1.414-5.656 5.657-2.829-2.829-1.414 1.414L11.003 16z" />
                            </svg>
                            <svg class="icon-incorrect" xmlns="https://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <path d="M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10zm0-11.414L9.172 7.757 7.757 9.172 10.586 12l-2.829 2.828 1.415 1.415L12 13.414l2.828 2.829 1.415-1.415L13.414 12l2.829-2.828-1.415-1.415L12 10.586z" />
                            </svg>
                        </span>
                        <span class="option__value"> var colors = ["red", "green", "blue"]</span>
                    </span>
                </label>
                <label class="option">
                    <input type="radio" name="1" class="option__input">
                    <span class="option__item">
                        <span class="option__icon">
                            <svg class="icon-correct" xmlns="https://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <path d="M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10zm-.997-6l7.07-7.071-1.414-1.414-5.656 5.657-2.829-2.829-1.414 1.414L11.003 16z" />
                            </svg>
                            <svg class="icon-incorrect" xmlns="https://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <path d="M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10zm0-11.414L9.172 7.757 7.757 9.172 10.586 12l-2.829 2.828 1.415 1.415L12 13.414l2.828 2.829 1.415-1.415L13.414 12l2.829-2.828-1.415-1.415L12 10.586z" />
                            </svg>
                        </span>
                        <span class="option__value"> var colors = "red", "green", "blue"</span>
                    </span>
                </label>
            </div>
        </div>
        <div class="box-view__footer">
            <div class="box-actions form">
                <!-- <div class="box-actions__cell box-actions__cell-left">
                                            <input type="button" value="Back" class="btn btn--bordered-primary">
                                        </div> -->
                <div class="box-actions__cell box-actions__cell-right">
                    <input type="button" value="Skip" class="btn btn--transparent border-0 color-black style-italic">
                    <input type="submit" value="Save & Next" class="btn btn--primary">
                </div>
            </div>
        </div>
    </div>
</div>
<div class="flex-layout__small">
    <div class="box-view box-view--space box-flex">
        <div class="box-view__head margin-bottom-5">
            <h4>Attempt Summary</h4>
        </div>
        <div class="box-view__body">
            <nav class="attempt-list">
                <ul>
                    <li class="is-visited"><a href="#" class="attempt-action" title="Answered">1</a></li>
                    <li class="is-visited"><a href="#" class="attempt-action" title="Answered">2</a></li>
                    <li class="is-visited"><a href="#" class="attempt-action" title="Answered">3</a></li>
                    <li class="is-skip"><a href="#" class="attempt-action" title="Skip">4</a></li>
                    <li class="is-skip"><a href="#" class="attempt-action" title="Skip">5</a></li>
                    <li class="is-current"><a href="#" class="attempt-action" title="Current">6</a></li>
                    <li><a href="#" class="attempt-action">7</a></li>
                    <li><a href="#" class="attempt-action">8</a></li>
                    <li><a href="#" class="attempt-action">9</a></li>
                    <li><a href="#" class="attempt-action">10</a></li>
                    <li><a href="#" class="attempt-action">11</a></li>
                    <li><a href="#" class="attempt-action">12</a></li>
                    <li><a href="#" class="attempt-action">13</a></li>
                    <li><a href="#" class="attempt-action">14</a></li>
                    <li><a href="#" class="attempt-action">15</a></li>
                    <li><a href="#" class="attempt-action">16</a></li>
                    <li><a href="#" class="attempt-action">17</a></li>
                    <li><a href="#" class="attempt-action">18</a></li>
                    <li><a href="#" class="attempt-action">19</a></li>
                    <li><a href="#" class="attempt-action">20</a></li>
                    <li><a href="#" class="attempt-action">21</a></li>
                    <li><a href="#" class="attempt-action">22</a></li>
                    <li><a href="#" class="attempt-action">23</a></li>
                    <li><a href="#" class="attempt-action">24</a></li>
                    <li><a href="#" class="attempt-action">25</a></li>
                    <li><a href="#" class="attempt-action">26</a></li>
                    <li><a href="#" class="attempt-action">27</a></li>
                    <li><a href="#" class="attempt-action">28</a></li>
                    <li><a href="#" class="attempt-action">29</a></li>
                    <li><a href="#" class="attempt-action">30</a></li>
                </ul>
            </nav>
        </div>
        <div class="box-view__footer">
            <div class="legends margin-bottom-10">
                <h6>Legend</h6>
                <div class="legend-list">
                    <ul>
                        <li class="is-current"><span class="legend-list__item">Current Active</span></li>
                        <li class="is-answered"><span class="legend-list__item">Answered</span></li>
                        <li class="is-skip"><span class="legend-list__item">Not Answered</span></li>
                    </ul>
                </div>
            </div>
            <div class="box-actions form">
                <input type="button" value="Submit & Finish" class="btn btn--bordered-primary btn--block is-disabled">
            </div>
        </div>
    </div>
</div>