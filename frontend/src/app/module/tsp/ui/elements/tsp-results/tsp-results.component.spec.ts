import { ComponentFixture, TestBed, waitForAsync } from '@angular/core/testing';
import { IonicModule } from '@ionic/angular';

import { TspResultsComponent } from './tsp-results.component';
import { testProviders } from '@app/core/application/test/test-providers';

describe('TspResultsComponent', () => {
  let component: TspResultsComponent;
  let fixture: ComponentFixture<TspResultsComponent>;

  beforeEach(waitForAsync(() => {
    TestBed.configureTestingModule({
      declarations: [ TspResultsComponent ],
      imports: [IonicModule.forRoot()],
      providers: testProviders,
    }).compileComponents();

    fixture = TestBed.createComponent(TspResultsComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  }));

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
