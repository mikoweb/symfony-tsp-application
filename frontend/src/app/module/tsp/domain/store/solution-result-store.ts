import { observable } from 'mobx-angular';
import { Injectable } from '@angular/core';
import { makeObservable } from 'mobx';
import { Distance } from '@app/module/tsp/domain/vo/distance';
import { Location } from '@app/module/tsp/domain/vo/location';
import { Section } from '@app/module/tsp/domain/vo/section';

@Injectable({
  providedIn: 'root',
})
export class SolutionResultStore {
  @observable public length: Distance | null = null;
  @observable public path: Location[] = [];
  @observable public sections: Section[] = [];
  @observable public loading: boolean = false;

  constructor() {
    makeObservable(this);
  }

  public clear(): void {
    this.length = null;
    this.path = [];
    this.sections = [];
  }
}
