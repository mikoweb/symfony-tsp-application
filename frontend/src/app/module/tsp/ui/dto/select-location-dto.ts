export class SelectLocationDto {
  constructor(
    public readonly id: string,
    public readonly name: string,
    public readonly lat: number,
    public readonly lng: number,
  ) {}
}
