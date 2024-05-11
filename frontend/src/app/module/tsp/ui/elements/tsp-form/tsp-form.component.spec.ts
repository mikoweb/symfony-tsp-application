import { ComponentFixture, TestBed, waitForAsync } from '@angular/core/testing';
import { IonicModule } from '@ionic/angular';

import { TspFormComponent } from './tsp-form.component';
import { testProviders } from '@app/core/application/test/test-providers';

describe('TspFormComponent', () => {
  let component: TspFormComponent;
  let fixture: ComponentFixture<TspFormComponent>;

  beforeEach(waitForAsync(() => {
    TestBed.configureTestingModule({
      declarations: [ TspFormComponent ],
      imports: [IonicModule.forRoot()],
      providers: testProviders,
    }).compileComponents();

    fixture = TestBed.createComponent(TspFormComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  }));

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
